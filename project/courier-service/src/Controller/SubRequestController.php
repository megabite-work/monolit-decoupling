<?php

declare(strict_types=1);

namespace App\Controller;

use App\Common\Dto\Order;
use App\Service\CourierService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/courier')]
class SubRequestController extends AbstractController
{
    #[Route('/service-courier/deliveries', methods: 'POST')]
    public function createDelivery(Request $request, SerializerInterface $serializer, CourierService $courierService): JsonResponse
    {
        $newOrder = $serializer->deserialize($request->getContent(), Order::class, 'json');
        $result = $courierService->createDelivery($newOrder);

        return $this->json($result);
    }
}