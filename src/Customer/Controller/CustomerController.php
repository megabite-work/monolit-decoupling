<?php

declare(strict_types=1);

namespace App\Customer\Controller;

use App\Customer\Dto\CreateOrderDto;
use App\Customer\Service\CustomerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/customer', defaults: ['_format' => 'json'])]
class CustomerController extends AbstractController
{
    public function __construct(private readonly CustomerService $customerService) {}

    #[Route('/order', methods: 'POST')]
    public function createOrder(#[MapRequestPayload] CreateOrderDto $dto): JsonResponse
    {
        return $this->json(data: $this->customerService->createOrder($dto), status: Response::HTTP_CREATED, context: ['groups' => 'api']);
    }
}
