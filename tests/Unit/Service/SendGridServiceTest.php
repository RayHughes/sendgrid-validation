<?php

declare(strict_types=1);

namespace SendGridValidation\Tests\Unit\Service;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use SendGridValidation\Exception\CannotValidateEmailException;
use SendGridValidation\Service\SendGridService;

class SendGridServiceTest extends TestCase
{
    private const API_KEY = 'key';

    private const EMAIL = 'email@example.com';

    private Client $apiClient;

    private ResponseInterface $responseInterface;

    private StreamInterface $streamInterface;

    /**
     * @throws \SendGridValidation\Exception\CannotValidateEmailException
     */
    public function testThatThrowsException(): void
    {
        $this->expectException(CannotValidateEmailException::class);

        $this->apiClient->expects($this->once())
            ->method('post')
            ->with(
                $this->equalTo(SendGridService::SENDGRID_VALIDATION_URI),
                $this->equalTo([
                    RequestOptions::HEADERS => ['Authorization' => 'Bearer ' . self::API_KEY],
                    RequestOptions::JSON => ['email' => self::EMAIL],
                ]))
            ->willThrowException(new Exception());

        $this->responseInterface->expects($this->never())
            ->method('getBody');

        $this->streamInterface->expects($this->never())
            ->method('getContents');

        $service = $this->createService();

        $service->handleValidation(self::EMAIL);
    }

    /**
     * @throws \SendGridValidation\Exception\CannotValidateEmailException
     */
    public function testThatItHandlesValidation(): void
    {
        $this->apiClient->expects($this->once())
            ->method('post')
            ->with(
                $this->equalTo(SendGridService::SENDGRID_VALIDATION_URI),
                $this->equalTo([
                    RequestOptions::HEADERS => ['Authorization' => 'Bearer ' . self::API_KEY],
                    RequestOptions::JSON => ['email' => self::EMAIL],
                ]))
            ->willReturn($this->responseInterface);

        $this->responseInterface->expects($this->once())
            ->method('getBody')
            ->willReturn($this->streamInterface);

        $this->streamInterface->expects($this->once())
            ->method('getContents')
            ->willReturn(json_encode([]));

        $service = $this->createService();

        $this->assertIsArray($service->handleValidation(self::EMAIL));
    }

    protected function setup(): void
    {
        $this->apiClient = $this->createMock(Client::class);

        $this->responseInterface = $this->createMock(ResponseInterface::class);

        $this->streamInterface = $this->createMock(StreamInterface::class);
    }

    private function createService(): SendGridService
    {
        return new SendGridService(self::API_KEY, $this->apiClient);
    }
}
