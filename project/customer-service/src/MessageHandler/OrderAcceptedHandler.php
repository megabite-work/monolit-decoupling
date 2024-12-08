<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Common\Message\OrderAccepted;
use App\Entity\Order;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class OrderAcceptedHandler
{
    public function __construct(
        private OrderRepository $orderRepository,
        private EntityManagerInterface $em
    ) {}

    public function __invoke(OrderAccepted $message): void
    {
        $order = $this->orderRepository->find($message->getOrder()->getId());
        if (!$order) {
            return;
        }

        $order->setStatus(Order::STATUS_ACCEPTED);
        $this->em->flush();
    }
}
