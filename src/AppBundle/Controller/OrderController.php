<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Order;
use AppBundle\Entity\User;
use AppBundle\Form\OrderType;
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
        /** @var TokenStorage $storage */
        $storage = $this->get('security.token_storage');
        if (!$storage->getToken()->isAuthenticated()) {
            throw new UnauthorizedHttpException('ax');
        }
        /** @var User $user */
        $user    = $storage->getToken()->getUser();
        /** @var Order $order */
        $order   = new Order();
        /** @var StockManagementService $service */
        $service = $this->get('stock.management.service');
        $form       = $this->createForm(OrderType::class, $order);

        if ($form->isSubmitted() && $form->isValid()) {
            $order->setUser($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($order);
            $em->flush();
            if (in_array($order->getType(), [Order::TYPE_ADD_FOOD, Order::TYPE_ADD_WASTE])) {
                $service->addStock($order);
            } else if (in_array($order->getType(), [Order::TYPE_REQUEST_FOOD, Order::TYPE_REQUEST_WASTE])) {
                $service->subtractStock($order);
            }
        }
        return $this->render('default/addOrder.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route(name="check_availability", path="/check_availability")
     */
    public function checkAvailabilityAction(Request $request)
    {
        /** @var TokenStorage $storage */
        $storage = $this->get('security.token_storage');
        if (!$storage->getToken()->isAuthenticated()) {
            throw new UnauthorizedHttpException('ax');
        }
        $type = $request->request->get('type');
        if (!in_array($type, [Order::TYPE_REQUEST_FOOD, Order::TYPE_REQUEST_WASTE]) || empty($request->request->get('quantity'))) {
            return new JsonResponse('Nu am putut genera o comanda cu datele cerute.');
        }

        $type = $type === Order::TYPE_REQUEST_FOOD
            ? Order::TYPE_ADD_FOOD
            : Order::TYPE_ADD_WASTE;
        $stockManagementService = $this->get('stock.management.service');
        $response = $stockManagementService->checkStock($request->request->get('quantity'), $type);
        return empty($response)
            ? new JsonResponse($response)
            : new JsonResponse('Nu am gasit stock pentru datele cerute.');
    }
}