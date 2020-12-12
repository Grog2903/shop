<?php

namespace App\Repository;

use App\Entity\Cart;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Cart|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cart|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cart[]    findAll()
 * @method Cart[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cart::class);
    }

    public function findBySession($sessionId)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.session_id = :val')
            ->setParameter('val', $sessionId)
            ->getQuery()
            ->getResult();
    }

    public function saveCart(string $sessionId, Product $product)
    {
        $cart = $this->findOneBy(['session_id' => $sessionId, 'product' => $product]);
        if ($cart) {
            $cart->setQuantity($cart->getQuantity() + 1);
        } else {
            $cart = (new Cart())
                ->setQuantity(1)
                ->setSessionId($sessionId)
                ->setProduct($product);
        }

        return $cart;
    }

    public function removeCart($sessionId)
    {
        $this->createQueryBuilder('c')
            ->delete()
            ->where('c.session_id = :session')
            ->setParameter('session', $sessionId)
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Cart[] Returns an array of Cart objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Cart
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
