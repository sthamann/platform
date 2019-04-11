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

        return $this->renderStorefront('@Storefront/page/product-detail/index.html.twig', ['page' => $page]);
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
     * @Route("/detail/{productId}/rating", name="frontend.detail.rating.save", methods={"POST"})
     */
    public function saveRating(string $productId, RequestDataBag $data, SalesChannelContext $context): Response
    {
        /*
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
            echo 'Eingeloggt';
        }

        try {
            $this->productRatingService->saveRating($productId, $data, $context);
        } catch (ConstraintViolationException $formViolations) {
            return $this->forward('Shopware\Storefront\PageController\ProductPageController::index', ['productId' => $productId, 'formViolations' => $formViolations]);
        }

        // $this->accountService->login($data->get('email'), $context);

        return new RedirectResponse($this->generateUrl('frontend.detail.page', ['productId' => $productId]));
    }
}
