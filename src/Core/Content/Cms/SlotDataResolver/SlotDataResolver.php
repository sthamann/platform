<?php declare(strict_types=1);

namespace Shopware\Core\Content\Cms\SlotDataResolver;

use Shopware\Core\Checkout\CheckoutContext;
use Shopware\Core\Content\Cms\Aggregate\CmsSlot\CmsSlotCollection;
use Shopware\Core\Content\Cms\Aggregate\CmsSlot\CmsSlotEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Exception\InconsistentCriteriaIdsException;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;
use Shopware\Core\Framework\Routing\InternalRequest;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SlotDataResolver
{
    /**
     * @var SlotTypeDataResolverInterface[]
     */
    private $resolvers;

    /**
     * @var array
     */
    private $repositories;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param SlotTypeDataResolverInterface[] $resolvers
     * @param array                           $repositories
     * @param ContainerInterface              $container
     */
    public function __construct(iterable $resolvers, array $repositories, ContainerInterface $container)
    {
        $this->container = $container;

        foreach ($repositories as $entityName => $repository) {
            $this->repositories[$entityName] = $repository;
        }

        foreach ($resolvers as $resolver) {
            $this->resolvers[$resolver->getType()] = $resolver;
        }
    }

    public function resolve(CmsSlotCollection $slots, InternalRequest $request, CheckoutContext $context): CmsSlotCollection
    {
        $slotCriteriaList = [];

        /*
         * Collect criteria objects for each slot from resolver
         *
         * @var CmsSlotEntity
         */
        foreach ($slots as $slot) {
            $resolver = $this->resolvers[$slot->getType()] ?? null;
            if (!$resolver) {
                continue;
            }

            $collection = $resolver->collect($slot, $request, $context);
            if ($collection === null) {
                continue;
            }

            $slotCriteriaList[$slot->getUniqueIdentifier()] = $collection;
        }

        // reduce search requests by combining mergeable criteria objects
        [$directReads, $searches] = $this->optimizeCriteriaObjects($slotCriteriaList);

        // fetch data from storage
        $entities = $this->fetchByIdentifier($directReads, $context);
        $searchResults = $this->fetchByCriteria($searches, $context);

        // create result for each slot with the requested data
        foreach ($slots as $slotId => $slot) {
            $resolver = $this->resolvers[$slot->getType()] ?? null;
            if (!$resolver) {
                continue;
            }

            $result = new SlotDataResolveResult();

            $this->mapSearchResults($result, $slot, $slotCriteriaList, $searchResults);
            $this->mapEntities($result, $slot, $slotCriteriaList, $entities);

            $slot = $resolver->enrich($slot, $request, $context, $result);

            // replace with return value from enrich(), because it's allowed to change the entity type
            $slots->set($slotId, $slot);
        }

        return $slots;
    }

    /**
     * @param string[][]      $directReads
     * @param CheckoutContext $context
     *
     * @throws InconsistentCriteriaIdsException
     *
     * @return EntitySearchResult[]
     */
    private function fetchByIdentifier(array $directReads, CheckoutContext $context): array
    {
        $entities = [];

        foreach ($directReads as $definition => $ids) {
            $repository = $this->getStorefrontRepository($definition);
            if ($repository) {
                $entities[$definition] = $repository->search(new Criteria($ids), $context);
            } else {
                $repository = $this->getApiRepository($definition);
                $entities[$definition] = $repository->search(new Criteria($ids), $context->getContext());
            }
        }

        return $entities;
    }

    private function fetchByCriteria(array $searches, CheckoutContext $context): array
    {
        $searchResults = [];

        /**
         * @var string|EntityDefinition
         * @var Criteria[]              $criteriaObjects
         */
        foreach ($searches as $definition => $criteriaObjects) {
            foreach ($criteriaObjects as $criteriaHash => $criteria) {
                $repository = $this->getStorefrontRepository($definition);
                if ($repository) {
                    $result = $repository->search($criteria, $context);
                } else {
                    $repository = $this->getApiRepository($definition);
                    $result = $repository->search($criteria, $context->getContext());
                }

                $searchResults[$criteriaHash] = $result;
            }
        }

        return $searchResults;
    }

    /**
     * @param CriteriaCollection[] $criteriaCollections
     *
     * @return array
     */
    private function optimizeCriteriaObjects(array $criteriaCollections): array
    {
        $directReads = [];
        $searches = [];

        $criteriaCollection = $this->flattenCriteriaCollections($criteriaCollections);

        foreach ($criteriaCollection as $definition => $criteriaObjects) {
            $directReads[$definition] = [[]];
            $searches[$definition] = [];

            /** @var Criteria $criteria */
            foreach ($criteriaObjects as $key => $criteria) {
                if ($this->canBeMerged($criteria)) {
                    $directReads[$definition][] = $criteria->getIds();
                } else {
                    $criteriaHash = $this->hash($criteria);
                    $searches[$definition][$criteriaHash] = $criteria;
                }
            }
        }

        foreach ($directReads as $definition => $idLists) {
            $directReads[$definition] = array_merge(...$idLists);
        }

        return [
            array_filter($directReads),
            array_filter($searches),
        ];
    }

    private function canBeMerged(Criteria $criteria): bool
    {
        //paginated lists must be an own search
        if ($criteria->getOffset() !== null || $criteria->getLimit() !== null) {
            return false;
        }

        //sortings must be an own search
        if (\count($criteria->getSorting())) {
            return false;
        }

        //queries must be an own search
        if (\count($criteria->getQueries())) {
            return false;
        }

        if ($criteria->getAssociations()) {
            return false;
        }

        if ($criteria->getAggregations()) {
            return false;
        }

        $filters = array_merge(
            $criteria->getFilters(),
            $criteria->getPostFilters()
        );

        // any kind of filters must be an own search
        if (!empty($filters)) {
            return false;
        }

        if (empty($filters) && empty($criteria->getIds())) {
            return false;
        }

        return true;
    }

    /**
     * @param string|EntityDefinition $definition
     *
     * @return EntityRepositoryInterface
     */
    private function getApiRepository(string $definition): EntityRepositoryInterface
    {
        /** @var EntityRepositoryInterface $repository */
        $repository = $this->container->get($definition::getEntityName() . '.repository');

        return $repository;
    }

    /**
     * @param string|EntityDefinition $definition
     *
     * @return mixed|null
     */
    private function getStorefrontRepository(string $definition)
    {
        return $this->repositories[$definition::getEntityName()] ?? null;
    }

    private function flattenCriteriaCollections(array $criteriaCollections): array
    {
        $flattened = [];

        $criteriaCollections = array_values($criteriaCollections);

        foreach ($criteriaCollections as $collections) {
            foreach ($collections as $definition => $criteriaObjects) {
                $flattened[$definition] = array_merge($flattened[$definition] ?? [], array_values($criteriaObjects));
            }
        }

        return $flattened;
    }

    /**
     * @param SlotDataResolveResult $result
     * @param CmsSlotEntity         $slot
     * @param CriteriaCollection[]  $criteriaObjects
     * @param EntitySearchResult[]  $searchResults
     */
    private function mapSearchResults(SlotDataResolveResult $result, CmsSlotEntity $slot, array $criteriaObjects, array $searchResults): void
    {
        if (!isset($criteriaObjects[$slot->getUniqueIdentifier()])) {
            return;
        }

        foreach ($criteriaObjects[$slot->getUniqueIdentifier()] as $definition => $criterias) {
            foreach ($criterias as $key => $criteria) {
                $hash = $this->hash($criteria);
                if (!isset($searchResults[$hash])) {
                    continue;
                }

                $result->add($key, $searchResults[$hash]);
            }
        }
    }

    /**
     * @param SlotDataResolveResult $result
     * @param CmsSlotEntity         $slot
     * @param CriteriaCollection[]  $criteriaObjects
     * @param EntitySearchResult[]  $entities
     */
    private function mapEntities(SlotDataResolveResult $result, CmsSlotEntity $slot, array $criteriaObjects, array $entities): void
    {
        if (!isset($criteriaObjects[$slot->getUniqueIdentifier()])) {
            return;
        }

        foreach ($criteriaObjects[$slot->getUniqueIdentifier()] as $definition => $criterias) {
            foreach ($criterias as $key => $criteria) {
                if (!$this->canBeMerged($criteria)) {
                    continue;
                }

                if (!isset($entities[$definition])) {
                    continue;
                }

                $ids = $criteria->getIds();
                $filtered = $entities[$definition]->filter(function (Entity $entity) use ($ids) {
                    return in_array($entity->getUniqueIdentifier(), $ids, true);
                });

                $result->add($key, $filtered);
            }
        }
    }

    private function hash(Criteria $criteria): string
    {
        return md5(serialize($criteria));
    }
}