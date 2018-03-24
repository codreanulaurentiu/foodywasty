<?php
/**
 * Created by PhpStorm.
 * User: laurentiu
 * Date: 3/24/18
 * Time: 7:40 PM
 */

namespace AppBundle\Repository;

use AppBundle\Entity\Order;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class OrderRepository extends EntityRepository
{
    /**
     * @param User $user
     * @return Order[]
     */
    public function getOrdersByUser(User $user)
    {
        return $this->createQueryBuilder('o')
            ->where('o.user = :user')
            ->setParameter('user', $user)
            ->getQuery()->getResult();
    }

    /**
     * @param User $user
     * @return Order[]
     */
    public function getUpcomingOrders(User $user)
    {
        return $this->createQueryBuilder('o')
            ->where('o.user = :user')
            ->andWhere('o.pickUpDate >= CURRENT_TIMESTAMP()')
            ->orderBy('o.pickUpDate', 'asc')
            ->setParameter('user', $user)
            ->getQuery()->getResult();
    }

    /**
     * @param User $user
     * @return Order[]
     */
    public function getPastOrders(User $user)
    {
        return $this->createQueryBuilder('o')
            ->where('o.user = :user')
            ->andWhere('o.pickUpDate < CURRENT_TIMESTAMP()')
            ->orderBy('o.pickUpDate', 'desc')
            ->setParameter('user', $user)
            ->getQuery()->getResult();
    }
}