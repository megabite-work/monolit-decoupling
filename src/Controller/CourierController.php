<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\ChangeDeliveryStatusDto;
use App\Service\CourierService;
use App\Service\CustomerService;
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
        return $this->json(
            data: $this->courierService->changeDeliveryStatus($dto, $customerService),
            context: ['groups' => 'api']
        );
    }
}
