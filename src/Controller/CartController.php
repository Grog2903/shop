<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\CartRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @var SessionInterface
     */
    private SessionInterface $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
        $this->session->start();
    }

    /**
     * @Route("/cart", name="cart")
     *
     * @param CartRepository $repository
     *
     * @return Response
     */
    public function index(CartRepository $repository): Response
    {
        dd($repository);
        return $this->render('cart/index.html.twig', [
            'products' => $repository->findBySession($this->session->getId())
        ]);
    }

    /**
     * @Route("cart/reset", name="reset_cart")
     *
     * @param CartRepository $repository
     *
     * @return RedirectResponse
     */
    public function removeCart(CartRepository $repository)
    {
        $repository->removeCart($this->session->getId());

        return $this->redirectToRoute('products_product');
    }

    /**
     * @Route("/cart/{id}", name="add_cart")
     *
     * @param Product $product
     * @param EntityManagerInterface $em
     * @param Request $request
     * @param CartRepository $repository
     *
     * @return RedirectResponse
     */
    public function addCart(Product $product, EntityManagerInterface $em, Request $request, CartRepository $repository)
    {
        $cart = $repository->saveCart($this->session->getId(), $product);

        $em->persist($cart);
        $em->flush();

        return $this->redirect($request->headers->get('referer'));
    }
}
