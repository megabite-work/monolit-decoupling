<?php

declare(strict_types=1);

namespace App\Restaurant\Controller;

use App\Common\Dto\Order;
use App\Restaurant\Service\RestaurantService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class SubRequestController extends AbstractController
{
    #[Route('/service-restaurant/restaurants/{restaurantId}', methods: 'GET')]
    public function getRestaurant(int $restaurantId, RestaurantService $restaurantService): JsonResponse
    {
        return $this->json($restaurantService->getRestaurant($restaurantId));
    }

    #[Route('/service-restaurant/orders/actions/accept', methods: 'POST')]
    public function acceptOrder(Request $request, SerializerInterface $serializer, RestaurantService $restaurantService): JsonResponse
    {
        $newOrder = $serializer->deserialize($request->getContent(), Order::class, 'json');
        $result = $restaurantService->acceptOrder($newOrder);

        return $this->json($result);
    }
}
