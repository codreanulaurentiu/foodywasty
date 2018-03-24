<?php
/**
 * Created by PhpStorm.
 * User: laurentiu
 * Date: 3/24/18
 * Time: 2:52 PM
 */

namespace AppBundle\Service;

use AppBundle\Entity\Order;
use AppBundle\Entity\Stock;
use AppBundle\Repository\StockRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraints\DateTime;

class StockManagementService
{
    /** @var  EntityManager */
    private $entityManager;

    /**
     * StockManagementService constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function addStock(Order $order)
    {
        $stock = new Stock();

        $stock->setOrder($order);
        $stock->setRemainingQuantity($order->getQuantity());
        $stock->setAvailabilityDate($order->getPickUpDate());
        $stock->setType($order->getType());

        $this->entityManager->persist($stock);
        $this->entityManager->flush();
    }

    public function subtractStock(Order $order)
    {
        /** @var StockRepository $repo */
        $repo = $this->entityManager->getRepository(Stock::class);

        $stocks = $repo->getAllAvailableStocks($order->getType());
        $remaining = $order->getQuantity();

        /** @var Stock $stock */
        foreach ($stocks as $stock) {
            if ($remaining <= 0) {
                $this->entityManager->flush();
                return;
            }

            $currentStock = $stock->getRemainingQuantity();
            $stock->setRemainingQuantity($currentStock - min($remaining, $currentStock));
            $remaining -= min($remaining, $currentStock);

        }
    }


    public function checkStock(int $quantity, int $type)
    {
        /** @var StockRepository $repo */
        $repo = $this->entityManager->getRepository(Stock::class);

        $stocks = $repo->getAllAvailableStocks($type);

        $partialStock = 0;
        $currentDate = $stocks[0]->getAvailabilityDate();

        /** @var Stock $stock */
        foreach ($stocks as $stock) {
            $currentDate = $stock->getAvailabilityDate();
            $partialStock += $stock->getRemainingQuantity();

            if ($partialStock >= $quantity) {
                return $currentDate < (new \DateTime()) ? new \DateTime() :  $currentDate;
            }
        }

        return null;
    }
}