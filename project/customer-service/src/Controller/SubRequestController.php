<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\CustomerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/customer')]
class SubRequestController extends AbstractController
{
    #[Route('/service-customer/orders', methods: 'POST')]
    public function createDelivery(Request $request, CustomerService $customerService): JsonResponse
    {
        $orderId = (int) $request->getPayload()->get('orderId');
        $newOrderStatus = (string) $request->getPayload()->get('newOrderStatus');
        $customerService->changeOrderStatus($orderId, $newOrderStatus);

        return new JsonResponse();
    }
}
