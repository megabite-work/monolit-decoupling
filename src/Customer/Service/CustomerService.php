<?php

declare(strict_types=1);

namespace App\Customer\Service;

use App\Common\Client\CourierServiceClient;
use App\Common\Client\RestaurantServiceClient;
use App\Common\Dto\Order as OrderDto;
use App\Common\Exception\ErrorException;
use App\Customer\Dto\CreateOrderDto;
use App\Customer\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

readonly class CustomerService
{
    public function __construct(
        private RestaurantServiceClient $restaurantServiceClient,
        private CourierServiceClient $courierServiceClient,
        private EntityManagerInterface $customerEntityManager
    ) {}

    public function createOrder(CreateOrderDto $dto): Order
    {
        $restaurant = $this->restaurantServiceClient->getRestaurant($dto->getRestaurantId());
        $order = (new Order())
            ->setRestaurantId($restaurant->getId())
            ->setStatus(Order::STATUS_NEW);

        $orderDto = new Orderdto($order->getId(), $order->getStatus(), $order->getRestaurantId(), $order->getDeliveryId());
            
        if ($this->restaurantServiceClient->acceptOrder($orderDto)) {
            $order->setStatus(Order::STATUS_ACCEPTED);
            $delivery = $this->courierServiceClient->createDelivery($orderDto);
            $order->setDeliveryId($delivery->getId());
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
