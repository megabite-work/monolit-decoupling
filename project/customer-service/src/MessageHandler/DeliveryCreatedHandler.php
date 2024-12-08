<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Common\Message\DeliveryCreated;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(fromTransport: 'async', handles: DeliveryCreated::class)]
readonly class DeliveryCreatedHandler
{
    public function __construct(
        private OrderRepository $orderRepository,
        private EntityManagerInterface $em
    ) {}

    public function __invoke(DeliveryCreated $message): void
    {
        $order = $this->orderRepository->find($message->getDelivery()->getRelatedOrderId());
        if (!$order) {
            return;
        }

        $order->setDeliveryId($message->getDelivery()->getId());
        $this->em->flush();
    }
}
