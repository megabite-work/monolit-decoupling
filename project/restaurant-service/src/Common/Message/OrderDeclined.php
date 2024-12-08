<?php

declare(strict_types=1);

namespace App\Common\Message;

use App\Common\Dto\Order as OrderDto;

class OrderDeclined
{
    public function __construct(private OrderDto $order) {}

    public function getOrder(): OrderDto
    {
        return $this->order;
    }
}
    