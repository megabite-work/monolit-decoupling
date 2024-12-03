<?php

declare(strict_types=1);

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

#[AsEventListener('kernel.exception')]
class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $response = new JsonResponse(
            data: [
                'success' => false,
                'message' => $exception->getMessage(),
            ],
            status: $exception->getCode() ?? 500,
        );

        $event->setResponse($response);
    }
}
