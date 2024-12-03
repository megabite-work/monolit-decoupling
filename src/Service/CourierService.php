<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\ChangeDeliveryStatusDto;
use App\Entity\Delivery;
use App\Entity\Order;
use App\Exception\ErrorException;
use App\Repository\DeliveryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

readonly class CourierService
{
    public function __construct(
        private EntityManagerInterface $em,
        private DeliveryRepository $deliveryRepository
    ) {
    }

    public function createDelivery(Order $order): Delivery
    {
        $delivery = (new Delivery())
            ->setStatus(Delivery::STATUS_NEW)
            ->setRelatedOrder($order);

        $this->em->persist($delivery);
        $this->em->flush();

        return $delivery;
    }

    public function changeDeliveryStatus(ChangeDeliveryStatusDto $dto, CustomerService $customerService): Delivery
    {
        $delivery = $this->deliveryRepository->find($dto->getId());
        
        if (!$delivery || !$delivery->getRelatedOrder()?->getId()) {
            throw new ErrorException('Delivery or related order not found', Response::HTTP_BAD_REQUEST);
        }

        $delivery->setStatus($dto->getStatus());
        $customerService->changeOrderStatus($delivery->getRelatedOrder()?->getId(), $delivery->getStatus());
        $this->em->flush();

        return $delivery;
    }
}
