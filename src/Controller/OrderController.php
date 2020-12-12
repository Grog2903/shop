<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderSet;
use App\Repository\CartRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
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
     * @Route("/order", name="order")
     */
    public function index(CartRepository $repository, EntityManagerInterface $em): Response
    {
        $carts = $repository->findBySession($this->session->getId());

        if(empty($carts)){
            $this->addFlash('flash_message_err', 'Ваша корзина пуста, заказывать нечего');
            return $this->redirectToRoute('products_product');
        }

        if($this->isGranted('IS_AUTHENTICATED_FULLY')){
            $order = (new Order())
                ->setUser($this->getUser());
            foreach ($carts as $cart){
                $orderSet = (new OrderSet())
                    ->setProduct($cart->getProduct())
                    ->setQuantity($cart->getQuantity())
                    ->setPrice($cart->getProduct()->getPrice())
                    ->setOrderU($order);
                $em->persist($orderSet);
            }

            $em->persist($order);
            $em->flush();

            $repository->removeCart($this->session->getId());

            $this->addFlash('flash_message', 'Заказ оформлен');
            return $this->redirectToRoute('order_ready', ['id' => $order->getId()]);
        }

        $this->addFlash('flash_message', 'Для оформления заказа необходимо войти или зарегистрироваться');
        return $this->redirectToRoute('app_login');
    }

    /**
     * @Route("/order/{id}", name="order_ready")
     */
    public function showOrder(Order $order)
    {
        return $this->render('order/ready.html.twig', [
            'order' => $order,
        ]);
    }
}
