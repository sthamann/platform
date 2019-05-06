<?php declare(strict_types=1);

namespace Shopware\Storefront\Pagelet\Product\Review;

use Shopware\Core\Framework\Struct\Struct;
use Shopware\Storefront\Framework\Page\StorefrontSearchResult;

class ProductReviewPagelet extends Struct
{
    /**
     * @var string
     */
    private $productId;

    /**
     * @var StorefrontSearchResult
     */
    private $reviews;

    public function __construct(
        string $productId,
        StorefrontSearchResult $reviews
    ) {
        $this->productId = $productId;
        $this->reviews = $reviews;
    }

    public function getReviews(): StorefrontSearchResult
    {
        return $this->reviews;
    }

    public function setReviews(StorefrontSearchResult $reviews): ProductReviewPagelet
    {
        $this->reviews = $reviews;

        return $this;
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function setProductId(string $productId): ProductReviewPagelet
    {
        $this->productId = $productId;

        return $this;
    }
}
