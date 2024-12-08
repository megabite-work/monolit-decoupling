<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Common\Message\DeliveryStatusChanged;
use App\Service\CustomerService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(fromTransport: 'async', handles: DeliveryStatusChanged::class)]
readonly class DeliveryStatusChangedHandler
{
    public function __construct(private CustomerService $customerService) {}

    public function __invoke(DeliveryStatusChanged $message): void
    {
        $this->customerService->changeOrderStatus($message->getDelivery());
    }
}
