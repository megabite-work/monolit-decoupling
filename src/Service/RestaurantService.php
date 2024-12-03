<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Order;
use App\Entity\Restaurant;
use App\Exception\ErrorException;
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

    public function acceptOrder(Order $order): bool
    {
        return true;
    }
}
