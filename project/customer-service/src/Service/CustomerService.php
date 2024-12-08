<?php

declare(strict_types=1);

namespace App\Service;

use App\Common\Dto\Delivery as DeliveryDto;
use App\Common\Dto\Order as OrderDto;
use App\Common\Exception\ErrorException;
use App\Common\Message\OrderCreated;
use App\Dto\CreateOrderDto;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;

readonly class CustomerService
{
    public function __construct(
        private EntityManagerInterface $em,
        private MessageBusInterface $messageBus,
    ) {}

    public function createOrder(CreateOrderDto $dto): Order
    {
        $order = (new Order())
            ->setRestaurantId($dto->getRestaurantId())
            ->setStatus(Order::STATUS_NEW);

        $this->em->persist($order);
        $this->em->flush();
        
        $orderDto = new Orderdto($order->getId(), $order->getStatus(), $order->getRestaurantId());
        $this->messageBus->dispatch(new OrderCreated($orderDto));

        return $order;
    }

    public function changeOrderStatus(DeliveryDto $deliveryDto): void
    {
        $order = $this->em->find(Order::class, $deliveryDto->getRelatedOrderId())
            ?? throw new ErrorException('Order not found', Response::HTTP_BAD_REQUEST);

        $order->setStatus($deliveryDto->getStatus());
        $this->em->flush();
    }
}
