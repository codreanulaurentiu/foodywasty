<?php
/**
 * Created by PhpStorm.
 * User: laurentiu
 * Date: 3/24/18
 * Time: 2:29 PM
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Stock
 * @package AppBundle\Entity
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StockRepository")
 * @ORM\Table()
 */
class Stock
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Order
     * @ORM\OneToOne(targetEntity="Order")
     */
    private $order;

    /**
     * @var float
     *
     * @ORM\Column(type="decimal")
     */
    private $remainingQuantity;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date")
     */
    private $availabilityDate;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint")
     */
    private $type;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param Order $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    /**
     * @return float
     */
    public function getRemainingQuantity()
    {
        return (float) $this->remainingQuantity;
    }

    /**
     * @param float $remainingQuantity
     */
    public function setRemainingQuantity($remainingQuantity)
    {
        $this->remainingQuantity = $remainingQuantity;
    }

    /**
     * @return \DateTime
     */
    public function getAvailabilityDate()
    {
        return $this->availabilityDate;
    }

    /**
     * @param \DateTime $availabilityDate
     */
    public function setAvailabilityDate($availabilityDate)
    {
        $this->availabilityDate = $availabilityDate;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param int $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }
}