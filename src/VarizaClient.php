<?php

declare(strict_types=1);

namespace The6FallenAngel\VarizaLaravel;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use The6FallenAngel\VarizaLaravel\DTOs\PaymentLink;
use The6FallenAngel\VarizaLaravel\DTOs\PaymentLinkRequest;
use The6FallenAngel\VarizaLaravel\Exceptions\ApiException;
use The6FallenAngel\VarizaLaravel\Exceptions\RateLimitException;
use The6FallenAngel\VarizaLaravel\Exceptions\ValidationException;

final class VarizaClient
{
    private Client $client;

    public function __construct(
        private readonly string $apiToken,
        private readonly string $baseUrl,
        private readonly int $timeout = 30,
    ) {
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'timeout' => $this->timeout,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.$this->apiToken,
            ],
        ]);
    }

    /**
     * Create a payment link.
     *
     * @throws ValidationException
     * @throws RateLimitException
     * @throws ApiException
     */
    public function createPaymentLink(PaymentLinkRequest $request): PaymentLink
    {
        try {
            $response = $this->client->post('/pay', [
                'json' => $request->toArray(),
            ]);

            $body = $response->getBody()->getContents();
            $data = json_decode($body, true, 512, JSON_THROW_ON_ERROR);

            if (! is_array($data)) {
                throw new ApiException(
                    status: 0,
                    message: 'Invalid JSON response from Variza API.'
                );
            }

            return PaymentLink::fromArray($data);
        } catch (GuzzleException $e) {
            if (method_exists($e, 'getResponse') && $response = $e->getResponse()) {
                $status = $response->getStatusCode();
                $body = $response->getBody()->getContents();

                throw $this->exceptionFor($status, $body);
            }

            throw new ApiException(
                status: 0,
                message: $e->getMessage()
            );
        } catch (\JsonException $e) {
            throw new ApiException(
                status: 0,
                message: 'Failed to decode JSON response: '.$e->getMessage()
            );
        }
    }

    private function exceptionFor(int $status, string $body): ApiException
    {
        $message = 'Variza API request failed.';
        $errors = [];

        try {
            $data = json_decode($body, true, 512, JSON_THROW_ON_ERROR);

            if (is_array($data)) {
                $message = isset($data['message']) ? (string) $data['message'] : $message;
                $errors = isset($data['errors']) ? (array) $data['errors'] : [];
            }
        } catch (\JsonException) {
            // Ignore JSON parsing errors
        }

        return match ($status) {
            422 => new ValidationException(
                status: $status,
                errors: $errors,
                responseBody: $body,
                message: $message
            ),
            429 => new RateLimitException(
                status: $status,
                errors: $errors,
                responseBody: $body,
                message: $message
            ),
            default => new ApiException(
                status: $status,
                errors: $errors,
                responseBody: $body,
                message: $message
            ),
        };
    }
}
