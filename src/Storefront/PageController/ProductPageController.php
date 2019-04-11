<?php declare(strict_types=1);

namespace Shopware\Storefront\PageController;

use Shopware\Core\Checkout\Customer\Storefront\AccountRegistrationService;
use Shopware\Core\Content\Product\Storefront\ProductRatingService;
use Shopware\Core\Framework\Routing\InternalRequest;
use Shopware\Core\Framework\Validation\DataBag\RequestDataBag;
use Shopware\Core\Framework\Validation\Exception\ConstraintViolationException;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Framework\Controller\StorefrontController;
use Shopware\Storefront\Framework\Page\PageLoaderInterface;
use Shopware\Storefront\Page\Product\ProductPageLoader;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductPageController extends StorefrontController
{
    /**
     * @var ProductPageLoader|PageLoaderInterface
     */
    private $detailPageLoader;

    /**
     * @var ProductRatingService
     */
    private $productRatingService;

    public function __construct(PageLoaderInterface $detailPageLoader,ProductRatingService $productRatingService)
    {
        $this->detailPageLoader = $detailPageLoader;
        $this->productRatingService = $productRatingService;
    }

    /**
     * @Route("/detail/{productId}", name="frontend.detail.page", options={"seo"="true"}, methods={"GET"})
     */
    public function index(SalesChannelContext $context, InternalRequest $request): Response
    {
        $page = $this->detailPageLoader->load($request, $context);

        return $this->renderStorefront('@Storefront/page/product-detail/index.html.twig', ['page' => $page]);
    }

    /**
     * @Route("/detail/{productId}/rating", name="frontend.detail.rating.save", methods={"POST"})
     */
    public function saveRating(string $productId, RequestDataBag $data, SalesChannelContext $context): Response
    {

        $customer = $context->getCustomer();
        $languageId = $context->getContext()->getLanguageId();
        $salesChannelId = $context->getContext()->getSalesChannelId();

        /**
         * ProductPageController.php on line 55:
        RequestDataBag {#201 ▼
        #parameters: array:7 [▼
        "productId" => "0928425ff4714ae9aff1e54250e79b0e"
        "productVersion" => "0fa91ce3e96a4bc2be4bd9ce752c3425"
        "name" => "Stefan Hamann"
        "email" => "sth@shopware.com"
        "title" => "Voll behindert"
        "content" => "Absolut nicht zu empfehlen"
        "points" => "3"
        ]
        }
         */

        if ($context->getCustomer()) {
            //return $this->redirectToRoute('frontend.account.home.page');
            echo "Eingeloggt";
        }


        /*
        try {
            $this->productRatingService->register($data, false, $context);
        } catch (ConstraintViolationException $formViolations) {
            return $this->forward('Shopware\Storefront\PageController\AccountPageController::register', ['formViolations' => $formViolations]);
        }*/

       // $this->accountService->login($data->get('email'), $context);

        return new RedirectResponse($this->generateUrl('frontend.detail.page',['productId'=>$productId]));
    }
}
