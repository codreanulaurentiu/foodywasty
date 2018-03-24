<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Order;
use AppBundle\Entity\User;
use AppBundle\Service\StockManagementService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Validator\Constraints\DateTime;

class OrderController extends Controller
{
    /**
     * @Route(name="add_order", path="/add_order")
     */
    public function addOrderAction(Request $request)
    {
        /** @var StockManagementService $service */
        $service = $this->get('stock.management.service');

//        var_dump($service->checkStock(1, 1));die;

        /** @var TokenStorage $storage */
        $storage = $this->get('security.token_storage');

        if (!$storage->getToken()->isAuthenticated()) {
            throw new UnauthorizedHttpException('ax');
        }

        /** @var User $user */
        $user = $storage->getToken()->getUser();
        $order = new Order();

        $date = new \DateTime();
        $date->setDate(2018, 3, 29);

        $order->setType(Order::TYPE_ADD_FOOD);
        $order->setQuantity(15);
        $order->setPickUpDate($date);
        $order->setUser($user);

        $em = $this->getDoctrine()->getManager();
        $em->persist($order);
        $em->flush();

        $service->subtractStock($order);

        return new JsonResponse(['ok']);
    }
}