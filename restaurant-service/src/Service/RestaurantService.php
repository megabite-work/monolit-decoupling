<?php

declare(strict_types=1);

namespace App\Service;

use App\Common\Dto\Order;
use App\Common\Exception\ErrorException;
use App\Entity\Restaurant;
use App\Repository\RestaurantRepository;
use Symfony\Component\HttpFoundation\Response;

readonly class RestaurantService
{
    public function __construct(private RestaurantRepository $restaurantRepository) {}

    public function getRestaurant(int $restaurantId): ?Restaurant
    {
        return $this->restaurantRepository->find($restaurantId)
            ?? throw new ErrorException('Restaurant not found', Response::HTTP_BAD_REQUEST);
    }

    public function acceptOrder(Order $orderDto): bool
    {
        return true;
    }
}
