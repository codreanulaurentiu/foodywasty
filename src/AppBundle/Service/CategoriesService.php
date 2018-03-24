<?php
namespace AppBundle\Service;


use AppBundle\Entity\Category;
use AppBundle\Repository\CategoryRepository;
use Doctrine\ORM\EntityManager;

class CategoriesService
{
    /** @var  EntityManager */
    private $entityManager;

    /**
     * CategoriesService constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getCategories()
    {
        /** @var CategoryRepository $categoriesRepository */
        $categoriesRepository = $this->entityManager->getRepository(Category::class);
        $categories = $categoriesRepository->getCategories();
        var_dump($categories);die;
        return $categoriesRepository->getCategories();
    }

}