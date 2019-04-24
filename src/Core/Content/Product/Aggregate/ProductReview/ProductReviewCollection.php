<?php declare(strict_types=1);

namespace Shopware\Core\Content\Product\Aggregate\ProductReview;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void                add(ProductRatingEntity $entity)
 * @method void                set(string $key, ProductRatingEntity $entity)
 * @method ProductRatingEntity[]    getIterator()
 * @method ProductRatingEntity[]    getElements()
 * @method ProductRatingEntity|null get(string $key)
 * @method ProductRatingEntity|null first()
 * @method ProductRatingEntity|null last()
 */
class ProductReviewCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return ProductReviewEntity::class;
    }
}