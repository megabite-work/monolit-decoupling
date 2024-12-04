<?php

declare(strict_types=1);

namespace App\Service;

use App\Common\Dto\Order;
use App\Common\Exception\ErrorException;
use App\Dto\ChangeDeliveryStatusDto;
use App\Entity\Delivery;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

readonly class CourierService
{
    public function __construct(private EntityManagerInterface $em) {}

    public function createDelivery(Order $orderDto): Delivery
    {
        $delivery = (new Delivery())
            ->setStatus(Delivery::STATUS_NEW)
            ->setRelatedOrderId($orderDto->getId());

        $this->em->persist($delivery);
        $this->em->flush();

        return $delivery;
    }

    public function changeDeliveryStatus(ChangeDeliveryStatusDto $dto): Delivery
    {
        $delivery = $this->em->find(Delivery::class, $dto->getId());

        if (!$delivery || !$delivery->getRelatedOrderId()) {
            throw new ErrorException('Delivery or related order not found', Response::HTTP_BAD_REQUEST);
        }

        $delivery->setStatus($dto->getStatus());
        $this->em->flush();

        return $delivery;
    }
}
