<?php

declare(strict_types=1);

namespace App\Customer\Service;

use App\Common\Exception\ErrorException;
use App\Courier\Service\CourierService;
use App\Customer\Dto\CreateOrderDto;
use App\Customer\Entity\Order;
use App\Restaurant\Service\RestaurantService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

readonly class CustomerService
{
    public function __construct(
        private RestaurantService $restaurantService,
        private CourierService $deliveryService,
        private EntityManagerInterface $customerEntityManager
    ) {}

    public function createOrder(CreateOrderDto $dto): Order
    {
        $restaurant = $this->restaurantService->getRestaurant($dto->getRestaurantId());
        $order = (new Order())
            ->setRestaurantId($restaurant->getId())
            ->setStatus(Order::STATUS_NEW);

        if ($this->restaurantService->acceptOrder($order->getId())) {
            $order->setStatus(Order::STATUS_ACCEPTED);
            $newDelivery = $this->deliveryService->createDelivery($order->getId());
            $order->setDeliveryId($newDelivery->getId());
        } else {
            $order->setStatus(Order::STATUS_DECLINED);
        }

        $this->customerEntityManager->persist($order);
        $this->customerEntityManager->flush();

        return $order;
    }

    public function changeOrderStatus(int $orderId, string $orderStatus): void
    {
        $order = $this->customerEntityManager->find(Order::class, $orderId)
            ?? throw new ErrorException('Order not found', Response::HTTP_BAD_REQUEST);

        $order->setStatus($orderStatus);
    }
}
