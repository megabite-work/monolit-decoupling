<?php

declare(strict_types=1);

namespace App\Common\EventListener;

use App\Common\Exception\ErrorException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

#[AsEventListener(event: 'kernel.exception')]
class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if (!$exception instanceof ErrorException) {
            return;
        }

        $response = new JsonResponse(
            data: [
                'success' => false,
                'message' => $exception->getMessage(),
            ],
            status: $exception->getCode() ?: Response::HTTP_INTERNAL_SERVER_ERROR,
        );

        $event->setResponse($response);
    }
}
