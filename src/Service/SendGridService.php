<?php

declare(strict_types=1);

namespace SendGridValidation\Service;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use SendGridValidation\Exception\CannotValidateEmailException;

class SendGridService
{
    public const SENDGRID_VALIDATION_URI = 'https://api.sendgrid.com/v3/validations/email';

    private const DEFAULT_TIMEOUT = 3.0;

    private string $apiKey;

    private Client $apiClient;

    public function __construct(string $apiKey, ?Client $apiClient = null)
    {
        $this->apiKey = $apiKey;
        $this->apiClient = $apiClient ?? new Client(['timeout' => self::DEFAULT_TIMEOUT]);
    }

    /**
     * @throws \SendGridValidation\Exception\CannotValidateEmailException
     * @psalm-suppress PossiblyUndefinedMethod
     */
    public function handleValidation(string $email): array
    {
        try {
            $response = $this->apiClient->post(self::SENDGRID_VALIDATION_URI, [
                RequestOptions::HEADERS => ['Authorization' => 'Bearer ' . $this->apiKey],
                RequestOptions::JSON => ['email' => $email],
            ]);
        } catch (GuzzleException|Exception $exception) {
            throw new CannotValidateEmailException($exception->getMessage(), (int) $exception->getCode());
        }

        return json_decode($response->getBody()->getContents(), true);
    }
}
