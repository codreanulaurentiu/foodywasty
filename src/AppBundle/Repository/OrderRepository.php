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

    public function getOrderQuantitiesByCategory(User $user)
    {
        $result = $this->createQueryBuilder('o')
            ->select('c.name, SUM(o.quantity)')
            ->innerJoin('AppBundle\Entity\Category', 'c', 'WITH', 'o.category = c.id')
            ->where('o.type = 1')
            ->andWhere('o.user = :user')
            ->groupBy('o.category')
            ->setParameter('user', $user)
            ->getQuery()->getArrayResult();

        $resultFormat = [];
        $resultFormat[] = ['Categorie', 'Cantitate'];

        foreach ($result as $resultItem) {
            $resultFormat[] = [$resultItem['name'], (float)$resultItem['1']];
        }
        return $resultFormat;
    }

    public function getOrderQuantitiesByDay(User $user)
    {
        $result = $this->createQueryBuilder('o')
            ->select('o.pickUpDate, SUM(o.quantity)')
            ->where('o.type = 1')
            ->andWhere('o.user = :user')
            ->groupBy('o.pickUpDate')
            ->setParameter('user', $user)
            ->getQuery()->getArrayResult();

        $resultFormat = [];
        $resultFormat[] = ['Data', 'Cantitate'];

        foreach ($result as $resultItem) {
            $resultFormat[] = [$resultItem['pickUpDate']->format('d-M-Y'), (float)$resultItem['1']];
        }
        return $resultFormat;
    }
}