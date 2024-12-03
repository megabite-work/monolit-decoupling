<?php

declare(strict_types=1);

namespace App\Customer\Controller;

use App\Customer\Service\CustomerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SubRequestController extends AbstractController
{
    #[Route('/service-customer/orders', methods: 'POST')]
    public function createDelivery(Request $request, CustomerService $customerService): JsonResponse
    {
        $orderId = (int) $request->get('orderId');
        $newOrderStatus = (string) $request->get('newOrderStatus');
        $customerService->changeOrderStatus($orderId, $newOrderStatus);

        return new JsonResponse();
    }
}
