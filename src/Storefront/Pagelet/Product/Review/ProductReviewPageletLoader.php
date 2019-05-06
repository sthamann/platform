<?php declare(strict_types=1);

namespace Shopware\Storefront\Pagelet\Product\Review;

use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Sorting\FieldSorting;
use Shopware\Core\Framework\Routing\Exception\MissingRequestParameterException;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Framework\Page\PageLoaderInterface;
use Shopware\Storefront\Framework\Page\StorefrontSearchResult;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;

final class ProductReviewPageletLoader implements PageLoaderInterface
{
    private const DEFAULT_SORT = 'DESC';
    private const LIMIT = 10;
    private const DEFAULT_PAGE = 1;
    private const ACTIVE_STATUS = 1;
    private const ALL_LANGUAGES = 'all';

    /**
     * @var EntityRepositoryInterface
     */
    private $reviewRepository;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(
        EntityRepositoryInterface $reviewRepository,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->reviewRepository = $reviewRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function load(Request $request, SalesChannelContext $context): ProductReviewPagelet
    {
        $criteria = $this->createReviewCriteria($request);

        $reviews = $this->getAllReviews($criteria, $context, $request);

        $pagelet = new ProductReviewPagelet($request->get('productId'), $reviews);

        $this->eventDispatcher->dispatch(
            ProductReviewPageletLoadedEvent::NAME,
            new ProductReviewPageletLoadedEvent($pagelet, $context, $request)
        );

        return $pagelet;
    }

    /**
     * get reviews with the users language
     * if there aren't any reviews then get them with any language
     */
    private function getAllReviews(Criteria $criteria, SalesChannelContext $context, Request $request): StorefrontSearchResult
    {
        if ($request->get('language') !== self::ALL_LANGUAGES) {
            $languageCriteria = clone $criteria;
            $languageCriteria->addFilter(new EqualsFilter('languageId', $this->getLanguageId($context)));

            $reviews = $this->reviewRepository->search($languageCriteria, $context->getContext())->getEntities();

            if ($reviews->count() > 0) {
                return StorefrontSearchResult::createFrom($reviews);
            }
        }

        $reviews = $this->reviewRepository->search($criteria, $context->getContext())->getEntities();

        return StorefrontSearchResult::createFrom($reviews);
    }

    /**
     * @throws MissingRequestParameterException
     */
    private function createReviewCriteria(Request $request): Criteria
    {
        $productId = $request->get('productId');
        if (!$productId) {
            throw new MissingRequestParameterException('productId');
        }

        $limit = (int) $request->get('limit', self::LIMIT);
        $page = (int) $request->get('page', self::DEFAULT_PAGE);
        $offset = $limit * ($page - 1);

        $sort = (string) $request->get('sort', self::DEFAULT_SORT);

        $criteria = (new Criteria())
            ->setLimit($limit)
            ->setOffset($offset)
            ->addSorting(new FieldSorting('createdAt', $sort))
            ->addFilter(
                new EqualsFilter('status', self::ACTIVE_STATUS),
                new EqualsFilter('productId', $productId)
            );

        $points = (int) $request->get('points');
        if ($points > 0) {
            $criteria->addFilter(new EqualsFilter('points', $points));
        }

        return $criteria;
    }

    private function getLanguageId(SalesChannelContext $context): string
    {
        if ($context->getCustomer()) {
            return $context->getCustomer()->getLanguageId();
        }

        return $context->getSalesChannel()->getLanguageId();
    }
}
