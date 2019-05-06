<?php declare(strict_types=1);

namespace Shopware\Storefront\PageletController;

use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Framework\Controller\StorefrontController;
use Shopware\Storefront\Framework\Page\PageLoaderInterface;
use Shopware\Storefront\Pagelet\Product\Review\ProductReviewPageletLoader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductPageletController extends StorefrontController
{
    /**
     * @var ProductReviewPageletLoader|PageLoaderInterface
     */
    private $reviewPageletLoader;

    public function __construct(PageLoaderInterface $reviewPageletLoader)
    {
        $this->reviewPageletLoader = $reviewPageletLoader;
    }

    /**
     * @Route("/reviews/{productId}", name="widgets.reviews", methods={"GET"}, defaults={"XmlHttpRequest"=true})
     */
    public function loadReview(Request $request, SalesChannelContext $context): Response
    {
        $page = $this->reviewPageletLoader->load($request, $context);

        return $this->renderStorefront('page/product-detail/review.html.twig', ['page' => $page, 'ratingSuccess' => $request->get('success')]);
    }
}
