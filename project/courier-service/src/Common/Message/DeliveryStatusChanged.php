<?php

declare(strict_types=1);

namespace App\Common\Message;

use App\Common\Dto\Delivery as DeliveryDto;
use Symfony\Component\Messenger\Attribute\AsMessage;

#[AsMessage(transport: 'async')]
readonly class DeliveryStatusChanged
{
    public function __construct(private DeliveryDto $delivery) {}

    public function getDelivery(): DeliveryDto
    {
        return $this->delivery;
    }
}
