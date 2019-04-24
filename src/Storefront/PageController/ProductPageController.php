<?php declare(strict_types=1);

namespace Shopware\Storefront\PageController;

use Shopware\Core\Checkout\Customer\Storefront\AccountRegistrationService;
use Shopware\Core\Content\Product\Storefront\ProductReviewService;
use Shopware\Core\Framework\Routing\InternalRequest;
use Shopware\Core\Framework\Validation\DataBag\RequestDataBag;
use Shopware\Core\Framework\Validation\Exception\ConstraintViolationException;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Framework\Controller\StorefrontController;
use Shopware\Storefront\Framework\Page\PageLoaderInterface;
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
    private $detailPageLoader;

    /**
     * @var ProductReviewService
     */
    private $productReviewService;

    public function __construct(PageLoaderInterface $detailPageLoader,ProductReviewService $productReviewService)
    {
        $this->detailPageLoader = $detailPageLoader;
        $this->productReviewService = $productReviewService;
    }

    /**
     * @Route("/detail/{productId}", name="frontend.detail.page", options={"seo"="true"}, methods={"GET"})
     */
    public function index(SalesChannelContext $context, InternalRequest $request, Request $symfonyRequest): Response
    {
        $page = $this->detailPageLoader->load($request, $context);

        $ratingSuccess = $symfonyRequest->get("success");

        return $this->renderStorefront('@Storefront/page/product-detail/index.html.twig', ['page' => $page,'ratingSuccess'=>$ratingSuccess]);
    }

    /**
     * @Route("/detail/{productId}/rating", name="frontend.detail.review.save", methods={"POST"})
     */
    public function saveReview(string $productId, RequestDataBag $data, SalesChannelContext $context): Response
    {
        if ($context->getCustomer()) {
            //return $this->redirectToRoute('frontend.account.home.page');
            echo "Eingeloggt";
        }

        try {
            $this->productReviewService->saveReview($productId,$data,$context);

        } catch (ConstraintViolationException $formViolations) {

            return $this->forward('Shopware\Storefront\PageController\ProductPageController::index', ['productId'=>$productId,'success'=>-1,'formViolations' => $formViolations], ['productId' => $productId]);
        }

        return new RedirectResponse($this->generateUrl('frontend.detail.page',['productId'=>$productId,'success'=>1]));
    }
}
