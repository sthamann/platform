<?php declare(strict_types=1);

namespace Shopware\Storefront\Page\Product;

use Shopware\Core\Content\Cms\CmsPageEntity;
use Shopware\Core\Content\Product\SalesChannel\SalesChannelProductEntity;
use Shopware\Storefront\Framework\Page\PageWithHeader;
use Shopware\Storefront\Framework\Page\StorefrontSearchResult;

class ProductPage extends PageWithHeader
{
    /**
     * @var SalesChannelProductEntity
     */
    protected $product;

    /**
     * @var CmsPageEntity
     */
    protected $cmsPage;

    /**
     * @var StorefrontSearchResult
     */
    protected $reviews;

    public function getProduct(): SalesChannelProductEntity
    {
        return $this->product;
    }

    public function setProduct(SalesChannelProductEntity $product): void
    {
        $this->product = $product;
    }

    public function getCmsPage(): CmsPageEntity
    {
        return $this->cmsPage;
    }

    public function setCmsPage(CmsPageEntity $cmsPage): void
    {
        $this->cmsPage = $cmsPage;
    }

    public function getReviews(): StorefrontSearchResult
    {
        return $this->reviews;
    }

    public function setReviews(StorefrontSearchResult $reviews): void
    {
        $this->reviews = $reviews;
    }
}
