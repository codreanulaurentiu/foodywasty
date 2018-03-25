<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Order;
use AppBundle\Entity\User;
use AppBundle\Form\DonateType;
use AppBundle\Form\OrderType;
use AppBundle\Repository\OrderRepository;
use AppBundle\Service\MailService;
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
//        /** @var TokenStorage $storage */
//        $storage = $this->get('security.token_storage');
//        if (!$storage->getToken()->isAuthenticated()) {
//            throw new UnauthorizedHttpException('ax');
//        }
//
//        /** @var User $user */
//        $user = $storage->getToken()->getUser();
//
//        /** @var Order $order */
//        $order = new Order();
//
//        /** @var StockManagementService $service */
//        $service = $this->get('stock.management.service');
//        $form    = $this->createForm(OrderType::class, $order);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $order->setUser($user);
//            $em = $this->getDoctrine()->getManager();
//            $em->persist($order);
//            $em->flush();
//            if (in_array($order->getType(), [Order::TYPE_ADD_FOOD, Order::TYPE_ADD_WASTE])) {
//                $service->addStock($order);
//            } else if (in_array($order->getType(), [Order::TYPE_REQUEST_FOOD, Order::TYPE_REQUEST_WASTE])) {
//                $service->subtractStock($order);
//            }
//        }
//
//        return $this->render('default/addOrder.html.twig', [
//            'form' => $form->createView(),
//        ]);
    }

    /**
     * @Route(name="donate", path="/donate")
     */
    public function donateAction(Request $request)
    {
        $form    = $this->createForm(DonateType::class);

        /** @var TokenStorage $storage */
        $storage = $this->get('security.token_storage');
        if (!$storage->getToken()->isAuthenticated()) {
            throw new UnauthorizedHttpException('ax');
        }

        /** @var User $user */
        $user = $storage->getToken()->getUser();

        /** @var Order $order */
        $order = new Order();

        /** @var StockManagementService $service */
        $service = $this->get('stock.management.service');
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $order = new Order();

            $data = $form->getData();

            $order->setQuantity($data['quantity']);
            $order->setType($data['type']);
            $order->setCategory($data['category']);
            $order->setPickUpDate($data['pickUpDate']);
            $order->setUser($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($order);
            $em->flush();
            if (in_array($order->getType(), [Order::TYPE_ADD_FOOD, Order::TYPE_ADD_WASTE])) {
                $service->addStock($order);
            } else if (in_array($order->getType(), [Order::TYPE_REQUEST_FOOD, Order::TYPE_REQUEST_WASTE])) {
                $service->subtractStock($order);
            }

            $this->addFlash('success', 'Donatie plasata cu succes!');

            return $this->redirect('orders');
        }

        return $this->render('default/donate.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route(name="order", path="/order")
     */
    public function orderAction(Request $request)
    {
        $form    = $this->createForm(OrderType::class);

        /** @var TokenStorage $storage */
        $storage = $this->get('security.token_storage');
        if (!$storage->getToken()->isAuthenticated()) {
            throw new UnauthorizedHttpException('ax');
        }

        /** @var User $user */
        $user = $storage->getToken()->getUser();

        /** @var Order $order */
        $order = new Order();

        /** @var StockManagementService $service */
        $service = $this->get('stock.management.service');
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $order = new Order();

            $data = $form->getData();

            $order->setQuantity($data['quantity']);
            $order->setType($data['type']);
            $order->setCategory($data['category']);
            $order->setPickUpDate($data['pickUpDate']);
            $order->setUser($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($order);
            $em->flush();

            if (in_array($order->getType(), [Order::TYPE_ADD_FOOD, Order::TYPE_ADD_WASTE])) {
                $service->addStock($order);
            } else if (in_array($order->getType(), [Order::TYPE_REQUEST_FOOD, Order::TYPE_REQUEST_WASTE])) {
                $service->subtractStock($order);
            }
            $this->addFlash('success', 'Comanda plasata cu succes!');

            return $this->redirect('orders');
        }

        return $this->render('default/order.html.twig', [
            'form' => $form->createView()
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
            throw new UnauthorizedHttpException('Nu aveti permisiuni sa accesati acest url!');
        }
        $type = $request->query->get('type');
        if (!in_array($type, [Order::TYPE_REQUEST_FOOD, Order::TYPE_REQUEST_WASTE]) || empty($request->query->get('quantity'))) {
            return new JsonResponse('Nu am putut genera o comanda cu datele cerute.');
        }

        $type = $type == Order::TYPE_REQUEST_FOOD
            ? Order::TYPE_ADD_FOOD
            : Order::TYPE_ADD_WASTE;
        $stockManagementService = $this->get('stock.management.service');
        $response = $stockManagementService->checkStock($request->query->get('quantity'), $type);

        return !empty($response)
            ? new JsonResponse($response)
            : new JsonResponse(['error' => 'Nu am gasit stock pentru datele cerute.']);
    }

    /**
     * @Route(name="get_categories", path="/list_categories")
     */
    public function getCategories(Request $request)
    {
        /** @var TokenStorage $storage */
        $storage = $this->get('security.token_storage');
        if (!$storage->getToken()->isAuthenticated()) {
            throw new UnauthorizedHttpException('Nu aveti permisiuni sa accesati acest url!');
        }

        $categories = $this->get('categories.service')->getCategories();
        return new JsonResponse($categories);
    }

    /**
     * @Route(name="list_orders", path="/orders")
     */
    public function listOrders(Request $request)
    {
        /** @var OrderRepository $repo */
        $orderRepository = $this->getDoctrine()->getManager()->getRepository(Order::class);
        /** @var TokenStorage $storage */
        $storage = $this->get('security.token_storage');
        if (!$storage->getToken()->isAuthenticated()) {
            throw new UnauthorizedHttpException('Nu aveti permisiuni sa accesati acest url!');
        }

        /** @var User $user */
        $user = $storage->getToken()->getUser();
        $mostWastedCategories = $orderRepository->getOrderQuantitiesByCategory($user);
        $mostWastedDays = $orderRepository->getOrderQuantitiesByDay($user);

        return $this->render('default/orderListing.html.twig', [
            'past_orders' => $orderRepository->getPastOrders($user),
            'upcoming_orders' => $orderRepository->getUpcomingOrders($user),
            'mostWastedCategories' => json_encode($mostWastedCategories),
            'mostWastedDays' => json_encode($mostWastedDays),
        ]);
    }

    /**
     * @Route(name="show_order", path="/orders/{id}")
     */
    public function showOrder(Request $request, int $id)
    {
        $repo = $this->getDoctrine()->getManager()->getRepository(Order::class);

        $order = $repo->find($id);

        return $this->render('default/orderDetails.html.twig', [
            'order' => $order
        ]);
    }
}