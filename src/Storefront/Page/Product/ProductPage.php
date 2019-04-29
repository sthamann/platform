<?php declare(strict_types=1);

namespace Shopware\Storefront\Page\Product;

use Shopware\Core\Content\Cms\CmsPageEntity;
use Shopware\Core\Content\Product\SalesChannel\SalesChannelProductEntity;
use Shopware\Core\Content\Property\PropertyGroupCollection;
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
     * @var PropertyGroupCollection
     */
    protected $configuratorSettings;

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

    public function getConfiguratorSettings(): PropertyGroupCollection
    {
        return $this->configuratorSettings;
    }

    public function setConfiguratorSettings(PropertyGroupCollection $configuratorSettings): void
    {
        $this->configuratorSettings = $configuratorSettings;
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
