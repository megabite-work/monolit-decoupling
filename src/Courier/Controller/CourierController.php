<?php

declare(strict_types=1);

namespace App\Courier\Controller;

use App\Courier\Dto\ChangeDeliveryStatusDto;
use App\Courier\Service\CourierService;
use App\Customer\Service\CustomerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/courier', defaults: ['_format' => 'json'])]
class CourierController extends AbstractController
{
    public function __construct(private readonly CourierService $courierService) {}

    #[Route('/delivery', methods: 'PATCH')]
    public function changeDeliveryStatus(#[MapRequestPayload] ChangeDeliveryStatusDto $dto, CustomerService $customerService): JsonResponse
    {
        $delivery = $this->courierService->changeDeliveryStatus($dto);
        $customerService->changeOrderStatus($delivery->getRelatedOrderId(), $delivery->getStatus());
        
        return $this->json(
            data: $delivery,
            context: ['groups' => 'api']
        );
    }
}
