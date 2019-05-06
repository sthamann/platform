<?php declare(strict_types=1);

namespace Shopware\Storefront\Pagelet\Product\Review;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\Event\NestedEvent;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\HttpFoundation\Request;

class ProductReviewPageletLoadedEvent extends NestedEvent
{
    public const NAME = 'review.pagelet.loaded.event';

    /**
     * @var ProductReviewPagelet
     */
    protected $pagelet;

    /**
     * @var SalesChannelContext
     */
    protected $context;

    /**
     * @var Request
     */
    protected $request;

    public function __construct(ProductReviewPagelet $pagelet, SalesChannelContext $context, Request $request)
    {
        $this->pagelet = $pagelet;
        $this->context = $context;
        $this->request = $request;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function getContext(): Context
    {
        return $this->context->getContext();
    }

    public function getSalesChannelContext(): SalesChannelContext
    {
        return $this->context;
    }

    public function getPagelet(): ProductReviewPagelet
    {
        return $this->pagelet;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }
}
