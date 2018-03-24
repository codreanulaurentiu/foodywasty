<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

class UserRepository extends EntityRepository implements UserLoaderInterface
{
    public function loadUserByUsername($username)
    {
        return $this->createQueryBuilder('u')
            ->where('u.username = :username OR u.email = :email')
            ->setParameter('username', $username)
            ->setParameter('email', $username)
            ->getQuery()
            ->getOneOrNullResult();
    }


    public function checkUserCredentials(string $username, string $password)
    {
        return $this->createQueryBuilder('u')
            ->where('u.username = :username')
            ->andWhere('u.password = :password')
            ->setParameter('username', $username)
            ->setParameter('password', sha1($password))
            ->getQuery()
            ->getOneOrNullResult();
    }
}