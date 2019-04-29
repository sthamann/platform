<?php declare(strict_types=1);

namespace Shopware\Core\Content\Product\Subscriber;

use Shopware\Core\Content\Product\ProductEntity;
use Shopware\Core\Content\Product\ProductEvents;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class ProductLoadedSubscriber
 * @package Shopware\Core\Content\Product\Subscriber
 */
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
        // AHRG!!!! TODO AT STH // IST DAS HIER RICHTIG ???
        /** @var ProductEntity $product */
        foreach ($event->getEntities() as $product) {
            $reviews = $product->getReviews();
            $avgPoints = 0;
            $ratingMatrix = array('0' => 0, '1' => 0,'2' => 0, '3' => 0,'4' => 0,'5' => 0);
            if ($reviews !== null){
                foreach ($reviews as $review){
                    $avgPoints += $review->getPoints();
                    $ratingMatrix[$review->getPoints()]++;
                }
                $reviewCount = $product->getReviews()->count();
                $avgPoints = $reviewCount > 0 ? $avgPoints / $reviewCount : 0;
            }

            $product->ratingAvg = $avgPoints;
            $product->ratingMatrix = $ratingMatrix;
        }
    }
}
