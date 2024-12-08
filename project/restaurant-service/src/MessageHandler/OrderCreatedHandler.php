<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Common\Message\OrderCreated;
use App\Service\RestaurantService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler(fromTransport: 'async', handles: OrderCreated::class)]
readonly class OrderCreatedHandler
{
    public function __construct(
        private MessageBusInterface $messageBus,
        private RestaurantService $restaurantService
    ) {}

    public function __invoke(OrderCreated $message)
    {
        $this->restaurantService->checkRestaurantAndAcceptOrDeclineOrder($message->getOrder());
    }
}
