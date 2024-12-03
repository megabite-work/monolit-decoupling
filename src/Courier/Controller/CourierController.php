<?php

declare(strict_types=1);

namespace App\Courier\Controller;

use App\Common\Client\CustomerServiceClient;
use App\Courier\Dto\ChangeDeliveryStatusDto;
use App\Courier\Service\CourierService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/courier', defaults: ['_format' => 'json'])]
class CourierController extends AbstractController
{
    public function __construct(private readonly CourierService $courierService) {}

    #[Route('/delivery', methods: 'PATCH')]
    public function changeDeliveryStatus(#[MapRequestPayload] ChangeDeliveryStatusDto $dto, CustomerServiceClient $customerServiceClient): JsonResponse
    {
        $delivery = $this->courierService->changeDeliveryStatus($dto);
        $customerServiceClient->changeOrderStatus($delivery->getRelatedOrderId(), $delivery->getStatus());
        
        return $this->json(
            data: $delivery,
            context: ['groups' => 'api']
        );
    }
}
