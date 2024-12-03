<?php

declare(strict_types=1);

namespace App\Courier\Service;

use App\Common\Exception\ErrorException;
use App\Courier\Dto\ChangeDeliveryStatusDto;
use App\Courier\Entity\Delivery;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

readonly class CourierService
{
    public function __construct(private EntityManagerInterface $courierEntityManager) {}

    public function createDelivery(int $orderId): Delivery
    {
        $delivery = (new Delivery())
            ->setStatus(Delivery::STATUS_NEW)
            ->setRelatedOrderId($orderId);

        $this->courierEntityManager->persist($delivery);
        $this->courierEntityManager->flush();

        return $delivery;
    }

    public function changeDeliveryStatus(ChangeDeliveryStatusDto $dto): Delivery
    {
        $delivery = $this->courierEntityManager->find(Delivery::class, $dto->getId());

        if (!$delivery || !$delivery->getRelatedOrderId()) {
            throw new ErrorException('Delivery or related order not found', Response::HTTP_BAD_REQUEST);
        }

        $delivery->setStatus($dto->getStatus());
        $this->courierEntityManager->flush();

        return $delivery;
    }
}
