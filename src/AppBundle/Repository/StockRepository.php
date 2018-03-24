<?php

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class StockRepository extends EntityRepository
{
    public function getStockByDay(int $type, int $minQuantity)
    {
        $qb = $this->createQueryBuilder('s');
        $qb->select('s.id, s.availabilityDate, SUM(s.remainingQuantity) as `sum`')
            ->where('s.type = :type')
            ->andWhere('s.remainingStock > 0')
            ->having('`sum` >= :quantity')
            ->setParameter('quantity', $minQuantity)
            ->setParameter('type', $type);

        return $qb->getQuery()->getResult();
    }


    /**
     * @param int $type
     * @return \Generator
     * @throws \Exception
     */
    public function getAllAvailableStocks(int $type)
    {
        $it = $this->createQueryBuilder('s')
            ->select('s')
            ->where('s.type = :type')
            ->andWhere('s.remainingQuantity > 0')
            ->orderBy('s.availabilityDate', 'ASC')
            ->setParameter('type', $type)
            ->getQuery()->iterate();

        $found = false;

        foreach ($it as $row) {
            yield $row[0];
            $found = true;
        }

        if (!$found) {
            throw new \Exception('Nu exista stock disponibil. Va rugam incercati cu alte date!');
        }
    }
}