<?php declare(strict_types=1);

namespace Shopware\Core\Framework\Demodata\Generator;

use Shopware\Core\Checkout\Customer\CustomerDefinition;
use Shopware\Core\Content\Product\Aggregate\ProductRating\ProductRatingDefinition;
use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Write\EntityWriterInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Write\WriteContext;
use Shopware\Core\Framework\Demodata\DemodataContext;
use Shopware\Core\Framework\Demodata\DemodataGeneratorInterface;
use Shopware\Core\Framework\Language\LanguageDefinition;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\SalesChannel\SalesChannelDefinition;

class ProductRatingGenerator implements DemodataGeneratorInterface
{
    /**
     * @var EntityWriterInterface
     */
    private $writer;

    public function __construct(EntityWriterInterface $writer)
    {
        $this->writer = $writer;
    }

    public function getDefinition(): string
    {
        return ProductRatingDefinition::class;
    }

    public function generate(int $numberOfItems, DemodataContext $context, array $options = []): void
    {
        $context->getConsole()->progressStart($numberOfItems);

        $payload = [];
        for ($i = 0; $i < $numberOfItems; ++$i) {
            $customers = $context->getIds(CustomerDefinition::class);
            $products = $context->getIds(ProductDefinition::class);
            $saleschannel = $context->getIds(SalesChannelDefinition::class);
            $languages = $context->getIds(LanguageDefinition::class);
            $points = [1, 2, 3, 4, 5];

            $payload[] = [
                'id' => Uuid::randomHex(),
                'productId' => $products[array_rand($products)],
                'customerId' => $customers[array_rand($customers)],
                'salesChannelId' => $saleschannel[array_rand($saleschannel)],
                'languageId' => $languages[array_rand($languages)],
                'title' => $$context->getFaker()->sentence,
                'content' => $context->getFaker()->text,
                'points' => $points[array_rand($points)],
                'status' => (bool) rand(0, 1),
            ];
        }

        $writeContext = WriteContext::createFromContext($context->getContext());

        foreach (array_chunk($payload, 100) as $chunk) {
            $this->writer->upsert(new ProductRatingDefinition(), $chunk, $writeContext);
            $context->getConsole()->progressAdvance(\count($chunk));
        }

        $context->getConsole()->progressFinish();
        $context->add(ProductRatingDefinition::class, ...array_column($payload, 'id'));
    }
}
