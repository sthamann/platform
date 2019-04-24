<?php declare(strict_types=1);

namespace Shopware\Core\Content\Product\Subscriber;

use Shopware\Core\Content\Product\ProductEntity;
use Shopware\Core\Content\Product\ProductEvents;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProductLoadedSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            ProductEvents::PRODUCT_LOADED_EVENT => [
                ['unserialize', 10]
            ],
        ];
    }

    public function unserialize(EntityLoadedEvent $event): void
    {
        /** @var ProductEntity $product */
        foreach ($event->getEntities() as $product) {
            $reviews = $product->getReviews();
            $avgPoints = 0;
            if ($reviews !== null){
                foreach ($reviews as $review){
                    $avgPoints += $review->getPoints();
                }
                $avgPoints = $avgPoints / count($product->getReviews());
            }

            $product->ratingAvg = $avgPoints;
        }
    }
}
