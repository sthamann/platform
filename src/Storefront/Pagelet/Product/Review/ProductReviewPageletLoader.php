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

    public function load(Request $request, SalesChannelContext $context): StorefrontSearchResult
    {
        $productId = $request->get('productId');

        if (!$productId) {
            throw new MissingRequestParameterException('productId');
        }

        $sort = (string) $request->get('sort', self::DEFAULT_SORT);
        $page = (int) $request->get('page', 1);
        $limit = (int) $request->get('limit', self::LIMIT);
        $offset = $limit * ($page - 1);

        $criteria = (new Criteria())
            ->setLimit($limit)
            ->setOffset($offset)
            ->addFilter(new EqualsFilter('status', 1), new EqualsFilter('productId', $productId))
            ->addSorting(new FieldSorting('createdAt', $sort));

        $reviews = $this->reviewRepository->search($criteria, $context->getContext())->getEntities();

        $pagelet = StorefrontSearchResult::createFrom($reviews);

        $this->eventDispatcher->dispatch(
            ProductReviewPageletLoadedEvent::NAME,
            new ProductReviewPageletLoadedEvent($pagelet, $context, $request)
        );

        return $pagelet;
    }
}
