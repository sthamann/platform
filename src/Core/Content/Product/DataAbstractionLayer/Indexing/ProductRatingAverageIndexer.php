<?php declare(strict_types=1);

namespace Shopware\Core\Content\Product\DataAbstractionLayer\Indexing;

use Doctrine\DBAL\Connection;
use Shopware\Core\Content\Product\Util\EventIdExtractor;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Dbal\Common\LastIdQuery;
use Shopware\Core\Framework\DataAbstractionLayer\Dbal\Indexing\IndexerInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenContainerEvent;
use Shopware\Core\Framework\Event\ProgressAdvancedEvent;
use Shopware\Core\Framework\Event\ProgressFinishedEvent;
use Shopware\Core\Framework\Event\ProgressStartedEvent;
use Shopware\Core\Framework\Uuid\Uuid;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ProductRatingAverageIndexer implements IndexerInterface
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var EventIdExtractor
     */
    private $eventIdExtractor;

    /**
     * @var Connection
     */
    private $connection;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        EventIdExtractor $eventIdExtractor,
        Connection $connection
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->eventIdExtractor = $eventIdExtractor;
        $this->connection = $connection;
    }

    public function index(\DateTimeInterface $timestamp): void
    {
        $context = Context::createDefaultContext();

        $iterator = $this->createIterator();

        $this->eventDispatcher->dispatch(
            ProgressStartedEvent::NAME,
            new ProgressStartedEvent('Start indexing product rating average', $iterator->fetchCount())
        );

        while ($ids = $iterator->fetch()) {
            $ids = array_map(function ($id) {
                return Uuid::fromBytesToHex($id);
            }, $ids);

            $this->update($ids, $context);

            $this->eventDispatcher->dispatch(
                ProgressAdvancedEvent::NAME,
                new ProgressAdvancedEvent(\count($ids))
            );
        }

        $this->eventDispatcher->dispatch(
            ProgressFinishedEvent::NAME,
            new ProgressFinishedEvent('Finished indexing product rating average')
        );
    }

    public function refresh(EntityWrittenContainerEvent $event): void
    {
        $ids = $this->eventIdExtractor->getProductIds($event);

        $this->update($ids, $event->getContext());
    }

    private function update(array $productIds, Context $context): void
    {
        if (empty($productIds)) {
            return;
        }

        $sql = <<<SQL
UPDATE product, product_review SET product.rating_average = (
    SELECT AVG(product_review.points)
    FROM product_review
    WHERE product_review.product_id = IFNULL(product.parent_id, product.id) AND status = 1
)
WHERE product_review.product_id = IFNULL(product.parent_id, product.id) AND status = 1
AND (product.id IN (:ids) OR product.parent_id IN (:ids))
SQL;

        $bytes = array_map(function ($id) {
            return Uuid::fromHexToBytes($id);
        }, $productIds);

        $this->connection->executeUpdate(
            $sql,
            ['ids' => $bytes],
            ['ids' => Connection::PARAM_STR_ARRAY]
        );
    }

    private function createIterator(): LastIdQuery
    {
        $query = $this->connection->createQueryBuilder();
        $query->select(['product.auto_increment', 'product.id']);
        $query->from('product');
        $query->andWhere('product.auto_increment > :lastId');
        $query->addOrderBy('product.auto_increment');

        $query->setMaxResults(50);

        $query->setParameter('lastId', 0);

        return new LastIdQuery($query);
    }
}
