<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ProductFormType;
use App\Repository\ProductRepository;
use App\Service\CategoryRender;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/admin/product", name="admin_product")
     * @param ProductRepository $productRepository
     * @return Response
     */
    public function allProducts(ProductRepository $productRepository, CategoryRender $categoryRender): Response
    {
        return $this->render('admin/product/index.html.twig', [
            'products' => $productRepository->findAll()
        ]);
    }

    /**
     * @Route("/admin/product/{slug}", name="admin_product_edit")
     */
    public function editProduct(Product $product, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(ProductFormType::class, $product);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $product = $form->getData();
            $em->persist($product);
            $em->flush();
            $this->addFlash('flash_message', 'Статья отредактирована');
            return $this->redirectToRoute('admin_product_edit', ['slug' => $product->getSlug()]);
        }

        return $this->render('admin/product/edit.html.twig',[
           'productForm' => $form->createView()
        ]);
    }
}
