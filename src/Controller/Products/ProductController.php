<?php

namespace App\Controller\Products;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Service\CategoryRender;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @var CategoryRender
     */
    private CategoryRender $categoryRender;

    public function __construct(CategoryRender $categoryRender)
    {
        $this->categoryRender = $categoryRender;
    }

    /**
     * @Route("/products", name="products_product")
     * @param ProductRepository $productRepository
     * @return Response
     */
    public function allProducts(ProductRepository $productRepository): Response
    {
        return $this->render('products/product/index.html.twig', [
            'categories' => $this->categoryRender->render(),
            'products' => $productRepository->findAll()
        ]);
    }

    /**
     * @Route("/products/category/{slug}", name="category")
     * @param Category $category
     * @return Response
     */
    public function showCategory(Category $category)
    {
        return $this->render('products/product/index.html.twig', [
            'categories' => $this->categoryRender->render(),
            'products' => $category->getProducts()
        ]);
    }
}
