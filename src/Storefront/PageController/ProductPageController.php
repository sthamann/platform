<?php declare(strict_types=1);

namespace Shopware\Storefront\PageController;

use Shopware\Core\Content\Product\SalesChannel\ProductRatingService;
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
use Shopware\Core\Content\Product\Storefront\ProductReviewService;

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
     * @var ProductRatingService
     */
    private $productRatingService;

    public function __construct(
        PageLoaderInterface $productPageLoader,
        ProductCombinationFinder $combinationFinder,
        ProductRatingService $productRatingService
    ) {
        $this->productPageLoader = $productPageLoader;
        $this->combinationFinder = $combinationFinder;
        $this->productRatingService = $productRatingService;
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
     * @Route("/detail/{productId}/rating", name="frontend.detail.review.save", methods={"POST"})
     */
    public function saveReview(string $productId, RequestDataBag $data, SalesChannelContext $context): Response
    {
        if ($context->getCustomer()) {
            //return $this->redirectToRoute('frontend.account.home.page');
            echo 'Eingeloggt';
        }

        try {
            $this->productRatingService->saveRating($productId, $data, $context);
        } catch (ConstraintViolationException $formViolations) {
            return $this->forward('Shopware\Storefront\PageController\ProductPageController::index', ['productId' => $productId, 'success' => -1, 'formViolations' => $formViolations], ['productId' => $productId]);
        }

        return new RedirectResponse($this->generateUrl('frontend.detail.page', ['productId' => $productId, 'success' => 1]));
    }
}
