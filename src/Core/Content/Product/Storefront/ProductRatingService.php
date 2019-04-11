<?php declare(strict_types=1);

namespace Shopware\Core\Content\Product\Storefront;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\Event\DataMappingEvent;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\Framework\Validation\BuildValidationEvent;
use Shopware\Core\Framework\Validation\DataBag\DataBag;
use Shopware\Core\Framework\Validation\DataValidationDefinition;
use Shopware\Core\Framework\Validation\DataValidator;
use Shopware\Core\Framework\Validation\Exception\ConstraintViolationException;
use Shopware\Core\Framework\Validation\ValidationServiceInterface;
use Shopware\Core\System\NumberRange\ValueGenerator\NumberRangeValueGeneratorInterface;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

class ProductRatingService
{
    /**
     * @var EntityRepositoryInterface
     */
    private $ratingRepository;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var DataValidator
     */
    private $validator;

    public function __construct(
        EntityRepositoryInterface $ratingRepository,
        EventDispatcherInterface $eventDispatcher,
        DataValidator $validator    ) {
        $this->ratingRepository = $ratingRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->validator = $validator;
    }

    public function saveRating(string $productId, DataBag $data,  SalesChannelContext $context): void
    {
        $customer = $context->getCustomer();
        $languageId = $context->getContext()->getLanguageId();
        $salesChannelId = $context->getContext()->getSalesChannelId();
        if (isset($customer)){
            $customerId = $context->getCustomer()->getId();
        }else {
            $customerId = null;
        }


        $this->validateRating($data, $context->getContext());

        $rating = array(
            'productId' => $productId,
            'customerId'=> $customerId,
            'salesChannelId' => $salesChannelId,
            'languageId' => $languageId,
            'externalUser' => $data->get("name"),
            'externalEmail' => $data->get("email"),
            'title' => $data->get("title"),
            'content' => $data->get("content"),
            'points' => $data->get("points")
        );

        $this->ratingRepository->create([$rating], $context->getContext());

    }

    private function validateRating(DataBag $data, Context $context): void
    {

        $definition = new DataValidationDefinition('product.create_rating');

        $definition->add('name', new NotBlank(), new Length(['min' => 5]));
        $definition->add('title', new NotBlank(), new Length(['min' => 5]));
        $definition->add('content', new NotBlank(), new Length(['min' => 40]));


        $definition->add('points',new GreaterThanOrEqual(1),new LessThanOrEqual(5));


        $this->validator->validate($data->all(), $definition);

        $violations = $this->validator->getViolations($data->all(), $definition);

        if (!$violations->count()) {
            return;
        }

        throw new ConstraintViolationException($violations, $data->all());
    }
}
