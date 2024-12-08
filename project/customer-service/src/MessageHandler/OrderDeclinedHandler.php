<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Common\Message\OrderDeclined;
use App\Entity\Order;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(fromTransport: 'async', handles: OrderDeclined::class)]
readonly class OrderDeclinedHandler
{
    public function __construct(
        private OrderRepository $orderRepository,
        private EntityManagerInterface $em
    ) {}

    public function __invoke(OrderDeclined $message): void
    {
        $order = $this->orderRepository->find($message->getOrder()->getId());
        if (!$order) {
            return;
        }

        $order->setStatus(Order::STATUS_DECLINED);
        $this->em->flush();
    }
}
