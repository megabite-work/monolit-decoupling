<?php

declare(strict_types=1);

namespace App\Common\Client;

use App\Common\Exception\ErrorException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

abstract class AbstractSubRequestClient
{
    public const IS_INTERNAL_REQUEST_ATTRIBUTE_KEY = 'is-internal-request';

    protected readonly Serializer $serializer;

    public function __construct(private readonly HttpKernelInterface $httpKernel)
    {
        $this->serializer = new Serializer(normalizers: [new ObjectNormalizer()], encoders: [new JsonEncoder()]);
    }

    protected function sendServiceRequest(string $uri, array $query = [], array $requestBody = [], string $method = Request::METHOD_GET): Response
    {
        foreach ([$query, $requestBody] as $payload) {
            $this->validatePayload($payload);
        }

        $request = new Request(
            query: $query,
            request: $requestBody,
            content: $this->serializer->encode($requestBody, 'json'),
        );

        $request->setMethod($method);
        $request->server->set('REQUEST_URI', $uri);
        $request->attributes->set(self::IS_INTERNAL_REQUEST_ATTRIBUTE_KEY, true);

        return $this->httpKernel->handle($request, HttpKernelInterface::SUB_REQUEST);
    }

    private function validatePayload($data): void
    {
        foreach ($data as $item) {
            if (is_array($item)) {
                $this->validatePayload($item);
            } elseif (!is_scalar($item) && !is_null($item)) {
                throw new ErrorException(message: 'Invalid payload', code: Response::HTTP_BAD_REQUEST);
            }
        }
    }
}
