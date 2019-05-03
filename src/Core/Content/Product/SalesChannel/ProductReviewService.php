<?php declare(strict_types=1);

namespace Shopware\Core\Content\Product\SalesChannel;

use Shopware\Core\Checkout\Cart\Exception\CustomerNotLoggedInException;
use Shopware\Core\Checkout\Cart\LineItem\LineItem;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Validation\DataBag\DataBag;
use Shopware\Core\Framework\Validation\DataValidationDefinition;
use Shopware\Core\Framework\Validation\DataValidator;
use Shopware\Core\Framework\Validation\Exception\ConstraintViolationException;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProductReviewService
{
    /**
     * @var EntityRepositoryInterface
     */
    private $reviewRepository;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var DataValidator
     */
    private $validator;
    /**
     * @var EntityRepositoryInterface
     */
    private $orderRepository;

    public function __construct(
        EntityRepositoryInterface $reviewRepository,
        EventDispatcherInterface $eventDispatcher,
        DataValidator $validator,
        EntityRepositoryInterface $orderRepository
    ) {
        $this->reviewRepository = $reviewRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->validator = $validator;
        $this->orderRepository = $orderRepository;
    }

    public function saveReview(string $productId, DataBag $data, SalesChannelContext $context): void
    {
        $customer = $context->getCustomer();
        $languageId = $context->getContext()->getLanguageId();
        $salesChannelId = $context->getSalesChannel()->getId();

        if (!isset($customer)) {
            throw new CustomerNotLoggedInException();
        }

        $customerId = $customer->getId();

        $this->validateReview($data, $context->getContext());

        // Spalte verifiedBuyer hinzufügen

        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('order.lineItems.payload.id', $productId));
        $criteria->addFilter(new EqualsFilter('order.lineItems.type', LineItem::PRODUCT_LINE_ITEM_TYPE));
        $criteria->addFilter(new EqualsFilter('order.customerId', $customer->getId()));

        // todo check if order didn't canceled
        $criteria->setLimit(1);

        $exists = $this->orderRepository->searchIds($criteria, $context->getContext());

        $buyed = \count($exists->getIds()) > 0;

        $rating = [
            'productId' => $productId,
            'customerId' => $customerId,
            'salesChannelId' => $salesChannelId,
            'languageId' => $languageId,
            'externalUser' => $data->get('name'),
            'externalEmail' => $data->get('email'),
            'title' => $data->get('title'),
            'content' => $data->get('content'),
            'points' => $data->get('points'),
        ];

        $this->reviewRepository->create([$rating], $context->getContext());
    }

    private function validateReview(DataBag $data, Context $context): void
    {
        $definition = new DataValidationDefinition('product.create_rating');

        $definition->add('name', new NotBlank(), new Length(['min' => 5]));
        $definition->add('title', new NotBlank(), new Length(['min' => 5]));
        $definition->add('content', new NotBlank(), new Length(['min' => 40]));

        $definition->add('points', new GreaterThanOrEqual(1), new LessThanOrEqual(5));

        $this->validator->validate($data->all(), $definition);

        $violations = $this->validator->getViolations($data->all(), $definition);

        if (!$violations->count()) {
            return;
        }

        throw new ConstraintViolationException($violations, $data->all());
    }
}
