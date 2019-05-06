<?php declare(strict_types=1);

namespace Shopware\Storefront\PageController;

use Shopware\Core\Content\Product\SalesChannel\ProductReviewService;
use Shopware\Core\Framework\Validation\DataBag\RequestDataBag;
use Shopware\Core\Framework\Validation\Exception\ConstraintViolationException;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Framework\Controller\StorefrontController;
use Shopware\Storefront\Framework\Page\PageLoaderInterface;
use Shopware\Storefront\Page\Product\Configurator\ProductCombinationFinder;
use Shopware\Storefront\Page\Product\ProductPageLoader;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductPageController extends StorefrontController
{
    /**
     * @var ProductPageLoader|PageLoaderInterface
     */
    private $productPageLoader;

    /**
     * @var ProductCombinationFinder
     */
    private $combinationFinder;

    /**
     * @var ProductReviewService
     */
    private $productReviewService;

    public function __construct(
        PageLoaderInterface $productPageLoader,
        ProductCombinationFinder $combinationFinder,
        ProductReviewService $productRatingService
    ) {
        $this->productPageLoader = $productPageLoader;
        $this->combinationFinder = $combinationFinder;
        $this->productReviewService = $productRatingService;
    }

    /**
     * @Route("/detail/{productId}", name="frontend.detail.page", methods={"GET"})
     */
    public function index(SalesChannelContext $context, Request $request): Response
    {
        $page = $this->productPageLoader->load($request, $context);

        $ratingSuccess = $request->get('success');

        return $this->renderStorefront('@Storefront/page/product-detail/index.html.twig', ['page' => $page, 'ratingSuccess' => $ratingSuccess]);
    }

    /**
     * @Route("/detail/{productId}/switch", name="frontend.detail.switch", methods={"POST"})
     */
    public function switch(string $productId, RequestDataBag $data, SalesChannelContext $context): RedirectResponse
    {
        $switchedOption = $data->get('switched');
        $newOptions = json_decode($data->get('options'), true);

        $redirect = $this->combinationFinder->find(
            $productId,
            $switchedOption,
            $newOptions,
            $context
        );

        return $this->redirectToRoute('frontend.detail.page', ['productId' => $redirect->getVariantId()]);
    }

    /**
     * @Route("/detail/{productId}/rating", name="frontend.detail.review.save", methods={"POST"}, defaults={"XmlHttpRequest"=true})
     */
    public function saveReview(string $productId, RequestDataBag $data, SalesChannelContext $context): Response
    {
        $this->denyAccessUnlessLoggedIn();

        try {
            $this->productReviewService->saveReview($productId, $data, $context);
        } catch (ConstraintViolationException $formViolations) {
            return $this->forward('Shopware\Storefront\PageletController\ProductPageletController::loadReview', ['productId' => $productId, 'success' => -1, 'formViolations' => $formViolations], ['productId' => $productId]);
        }

        return $this->forward('Shopware\Storefront\PageletController\ProductPageletController::loadReview', ['productId' => $productId, 'success' => 1]);
    }
}
