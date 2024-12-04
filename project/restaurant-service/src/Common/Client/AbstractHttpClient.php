<?php

declare(strict_types=1);

namespace App\Common\Client;

use App\Common\Exception\ErrorException;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

abstract class AbstractHttpClient
{
    public const IS_INTERNAL_REQUEST_ATTRIBUTE_KEY = 'is-internal-request';

    protected readonly Serializer $serializer;

    public function __construct(
        private readonly HttpClientInterface $client,
        #[Autowire('%api.secret.key%')]
        private readonly string $apiSecretKey,
    ) {
        $this->serializer = new Serializer(normalizers: [new ObjectNormalizer()], encoders: [new JsonEncoder()]);
    }

    protected function sendServiceRequest(string $uri, array $query = [], array $requestBody = [], string $method = Request::METHOD_GET): ResponseInterface
    {
        foreach ([$query, $requestBody] as $payload) {
            $this->validatePayload($payload);
        }

        $host = getenv('COMPOSE_PROJECT_NAME') ? 'http://' . getenv('COMPOSE_PROJECT_NAME') . '-nginx/api/' : 'http://nginx/api/';

        return $this->client->request(
            $method,
            $host . $this->getServiceName() . $uri,
            [
                'query' => $query,
                'body' => $this->serializer->serialize($requestBody, JsonEncoder::FORMAT),
                'headers' => [
                    'Content-Type' => 'application/json',
                    'X-Api-Secret' => $this->apiSecretKey,
                ],
            ]
        );
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

    abstract protected function getServiceName(): string;
}
