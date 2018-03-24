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
 * Class Order
 * @package AppBundle\Entity
 * @ORM\Entity()
 * @ORM\Table(
 *     name="orders"
 * )
 */
class Order
{
    const TYPE_ADD_FOOD = 1;
    const TYPE_REQUEST_FOOD = 2;
    const TYPE_ADD_WASTE = 3;
    const TYPE_REQUEST_WASTE = 4;

    /**
     * @var int
     * @ORM\Id;
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User")
     */
    private $user;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint")
     */
    private $type;

    /**
     * @var float
     *
     * @ORM\Column(type="decimal")
     */
    private $quantity;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date")
     */
    private $pickUpDate;

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
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

    /**
     * @return float
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param float $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * @return \DateTime
     */
    public function getPickUpDate()
    {
        return $this->pickUpDate;
    }

    /**
     * @param \DateTime $pickUpDate
     */
    public function setPickUpDate($pickUpDate)
    {
        $this->pickUpDate = $pickUpDate;
    }
}