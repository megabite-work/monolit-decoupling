<?php

declare(strict_types=1);

namespace App\Service;

use App\Common\Dto\Order as OrderDto;
use App\Common\Exception\ErrorException;
use App\Common\Message\OrderAccepted;
use App\Common\Message\OrderDeclined;
use App\Entity\Restaurant;
use App\Repository\RestaurantRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;

readonly class RestaurantService
{
    public function __construct(
        private RestaurantRepository $restaurantRepository,
        private MessageBusInterface $messageBus,
    ) {}

    public function getRestaurant(int $restaurantId): ?Restaurant
    {
        return $this->restaurantRepository->find($restaurantId)
            ?? throw new ErrorException('Restaurant not found', Response::HTTP_BAD_REQUEST);
    }

    public function acceptOrder(OrderDto $orderDto): void
    {
        $this->messageBus->dispatch(new OrderAccepted($orderDto));
    }

    public function declineOrder(OrderDto $orderDto): void
    {
        $this->messageBus->dispatch(new OrderDeclined($orderDto));
    }

    public function checkRestaurantAndAcceptOrDeclineOrder(OrderDto $orderDto)
    {
        try {
            $this->getRestaurant($orderDto->getRestaurantId());
            $this->acceptOrder($orderDto);
        } catch (\Throwable $th) {
            $this->declineOrder($orderDto);
        }
    }
}
