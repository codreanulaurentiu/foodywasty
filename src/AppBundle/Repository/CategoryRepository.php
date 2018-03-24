<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class CategoryRepository extends EntityRepository
{
    public function getCategories()
    {
        return $this->createQueryBuilder('c')
            ->select('c.name')
            ->setMaxResults(1000)
            ->getQuery()->getResult();
    }
}