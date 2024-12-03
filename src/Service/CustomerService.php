<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\CreateOrderDto;
use App\Entity\Order;
use App\Exception\ErrorException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

readonly class CustomerService
{
    public function __construct(
        private RestaurantService $restaurantService,
        private CourierService $deliveryService,
        private EntityManagerInterface $em
    ) {}

    public function createOrder(CreateOrderDto $dto): Order
    {
        $restaurant = $this->restaurantService->getRestaurant($dto->getRestaurantId());
        $order = (new Order())
            ->setRestaurant($restaurant)
            ->setStatus(Order::STATUS_NEW);

        if ($this->restaurantService->acceptOrder($order)) {
            $order->setStatus(Order::STATUS_ACCEPTED);
            $newDelivery = $this->deliveryService->createDelivery($order);
            $order->setDelivery($newDelivery);
        } else {
            $order->setStatus(Order::STATUS_DECLINED);
        }

        $this->em->persist($order);
        $this->em->flush();

        return $order;
    }

    public function changeOrderStatus(int $orderId, string $orderStatus): void
    {
        $order = $this->em->find(Order::class, $orderId)
            ?? throw new ErrorException('Order not found', Response::HTTP_BAD_REQUEST);

        $order->setStatus($orderStatus);
    }
}
