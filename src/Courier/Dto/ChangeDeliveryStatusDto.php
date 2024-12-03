<?php

declare(strict_types=1);

namespace App\Courier\Dto;

use App\Entity\Delivery;
use Symfony\Component\Validator\Constraints as Assert;

readonly class ChangeDeliveryStatusDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Positive]
        private int $id,
        #[Assert\Type('string')]
        #[Assert\Choice(choices: [
            Delivery::STATUS_COURIER_ASSIGNED,
            Delivery::STATUS_DELIVERING,
            Delivery::STATUS_FAILED,
            Delivery::STATUS_SUCCESSFUL,
        ])]
        private string $status
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}
