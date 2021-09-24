<?php

declare(strict_types=1);

namespace SendGridValidation\Tests\Unit;

use PHPUnit\Framework\TestCase;
use SendGridValidation\Dto\EmailValidationDto;
use SendGridValidation\EmailValidation;
use SendGridValidation\Repository\SendGridApiRepository;

class EmailValidationTest extends TestCase
{
    private const RISKY = 'Risky';

    private const INVALID = 'Invalid';

    private const VALID = 'Valid';

    private const EMAIL = 'email@example.com';

    private SendGridApiRepository $sendGridService;

    /**
     * @throws \SendGridValidation\Exception\CannotValidateEmailException
     */
    public function testThatEmailIsValidWithDefaults(): void
    {
        $sendGridRes = [[
            'verdict' => self::VALID,
            'score' => 1.0,
            'checks' => [
                'domain' => [
                    'is_suspected_disposable_address' => false,
                ],
            ],
        ]];

        $this->sendGridService->expects($this->once())
            ->method('handleValidation')
            ->with($this->identicalTo(self::EMAIL))
            ->willReturn($sendGridRes);

        $validation = new EmailValidation($this->sendGridService);

        $response = $validation->validate(self::EMAIL);

        $this->assertInstanceOf(EmailValidationDto::class, $response);
        $this->assertTrue($response->isValid);
        $this->assertTrue($response->isValidRisk);
        $this->assertTrue($response->isValidScore);
        $this->assertFalse($response->isDisposable);
        $this->assertFalse($response->hasSuggestion);
        $this->assertNull($response->suggestion);
    }

    /**
     * @throws \SendGridValidation\Exception\CannotValidateEmailException
     */
    public function testThatEmailIsInValidWithDefaults(): void
    {
        $sendGridRes = [[
            'verdict' => self::INVALID,
            'score' => 0,
            'checks' => [
                'domain' => [
                    'is_suspected_disposable_address' => false,
                ],
            ],
        ]];

        $this->sendGridService->expects($this->once())
            ->method('handleValidation')
            ->with($this->identicalTo(self::EMAIL))
            ->willReturn($sendGridRes);

        $validation = new EmailValidation($this->sendGridService);

        $response = $validation->validate(self::EMAIL);

        $this->assertInstanceOf(EmailValidationDto::class, $response);
        $this->assertFalse($response->isValid);
        $this->assertFalse($response->isValidRisk);
        $this->assertFalse($response->isValidScore);
        $this->assertFalse($response->isDisposable);
        $this->assertFalse($response->hasSuggestion);
        $this->assertNull($response->suggestion);
    }

    /**
     * @throws \SendGridValidation\Exception\CannotValidateEmailException
     */
    public function testThatEmailIsInValidWithDefaultsByScore(): void
    {
        $sendGridRes = [[
            'verdict' => self::RISKY,
            'score' => 0,
            'checks' => [
                'domain' => [
                    'is_suspected_disposable_address' => false,
                ],
            ],
        ]];

        $this->sendGridService->expects($this->once())
            ->method('handleValidation')
            ->with($this->identicalTo(self::EMAIL))
            ->willReturn($sendGridRes);

        $validation = new EmailValidation($this->sendGridService);

        $response = $validation->validate(self::EMAIL);

        $this->assertInstanceOf(EmailValidationDto::class, $response);
        $this->assertFalse($response->isValid);
        $this->assertTrue($response->isValidRisk);
        $this->assertFalse($response->isValidScore);
        $this->assertFalse($response->isDisposable);
        $this->assertFalse($response->hasSuggestion);
        $this->assertNull($response->suggestion);
    }

    /**
     * @throws \SendGridValidation\Exception\CannotValidateEmailException
     */
    public function testThatEmailIsValidWithCustomScore(): void
    {
        $sendGridRes = [[
            'verdict' => self::RISKY,
            'score' => 1,
            'checks' => [
                'domain' => [
                    'is_suspected_disposable_address' => false,
                ],
            ],
        ]];

        $this->sendGridService->expects($this->once())
            ->method('handleValidation')
            ->with($this->identicalTo(self::EMAIL))
            ->willReturn($sendGridRes);

        $validation = new EmailValidation(
            $this->sendGridService,
            true,
            true,
            false,
            0.5
        );

        $response = $validation->validate(self::EMAIL);

        $this->assertInstanceOf(EmailValidationDto::class, $response);
        $this->assertTrue($response->isValid);
        $this->assertTrue($response->isValidRisk);
        $this->assertTrue($response->isValidScore);
        $this->assertFalse($response->isDisposable);
        $this->assertFalse($response->hasSuggestion);
        $this->assertNull($response->suggestion);
    }

    /**
     * @throws \SendGridValidation\Exception\CannotValidateEmailException
     */
    public function testThatEmailIsInValidWithCustomScore(): void
    {
        $sendGridRes = [[
            'verdict' => self::RISKY,
            'score' => 0.5,
            'checks' => [
                'domain' => [
                    'is_suspected_disposable_address' => false,
                ],
            ],
        ]];

        $this->sendGridService->expects($this->once())
            ->method('handleValidation')
            ->with($this->identicalTo(self::EMAIL))
            ->willReturn($sendGridRes);

        $validation = new EmailValidation(
            $this->sendGridService,
            true,
            true,
            false,
            1
        );

        $response = $validation->validate(self::EMAIL);

        $this->assertInstanceOf(EmailValidationDto::class, $response);
        $this->assertFalse($response->isValid);
        $this->assertTrue($response->isValidRisk);
        $this->assertFalse($response->isValidScore);
        $this->assertFalse($response->isDisposable);
        $this->assertFalse($response->hasSuggestion);
        $this->assertNull($response->suggestion);
    }

    /**
     * @throws \SendGridValidation\Exception\CannotValidateEmailException
     */
    public function testThatEmailIsValidWithCustomScoreOnValid(): void
    {
        $sendGridRes = [[
            'verdict' => self::VALID,
            'score' => 1,
            'checks' => [
                'domain' => [
                    'is_suspected_disposable_address' => false,
                ],
            ],
        ]];

        $this->sendGridService->expects($this->once())
            ->method('handleValidation')
            ->with($this->identicalTo(self::EMAIL))
            ->willReturn($sendGridRes);

        $validation = new EmailValidation(
            $this->sendGridService,
            true,
            true,
            false,
            0.5
        );

        $response = $validation->validate(self::EMAIL);

        $this->assertInstanceOf(EmailValidationDto::class, $response);
        $this->assertTrue($response->isValid);
        $this->assertTrue($response->isValidRisk);
        $this->assertTrue($response->isValidScore);
        $this->assertFalse($response->isDisposable);
        $this->assertFalse($response->hasSuggestion);
        $this->assertNull($response->suggestion);
    }

    /**
     * @throws \SendGridValidation\Exception\CannotValidateEmailException
     */
    public function testThatEmailIsInValidWithSuggestion(): void
    {
        $sendGridRes = [[
            'verdict' => self::INVALID,
            'score' => 0,
            'local' => 'email',
            'suggestion' => 'example.com',
            'checks' => [
                'domain' => [
                    'is_suspected_disposable_address' => false,
                ],
            ],
        ]];

        $this->sendGridService->expects($this->once())
            ->method('handleValidation')
            ->with($this->identicalTo(self::EMAIL))
            ->willReturn($sendGridRes);

        $validation = new EmailValidation(
            $this->sendGridService,
            true,
            true,
            false,
            0.5
        );

        $response = $validation->validate(self::EMAIL);

        $this->assertInstanceOf(EmailValidationDto::class, $response);
        $this->assertFalse($response->isValid);
        $this->assertFalse($response->isValidRisk);
        $this->assertFalse($response->isValidScore);
        $this->assertFalse($response->isDisposable);
        $this->assertTrue($response->hasSuggestion);
        $this->assertEquals(self::EMAIL, $response->suggestion);
    }

    /**
     * @throws \SendGridValidation\Exception\CannotValidateEmailException
     */
    public function testThatEmailIsValidWhenDisposable(): void
    {
        $sendGridRes = [[
            'verdict' => self::RISKY,
            'score' => 1,
            'checks' => [
                'domain' => [
                    'is_suspected_disposable_address' => true,
                ],
            ],
        ]];

        $this->sendGridService->expects($this->once())
            ->method('handleValidation')
            ->with($this->identicalTo(self::EMAIL))
            ->willReturn($sendGridRes);

        $validation = new EmailValidation($this->sendGridService);

        $response = $validation->validate(self::EMAIL);

        $this->assertInstanceOf(EmailValidationDto::class, $response);
        $this->assertTrue($response->isValid);
        $this->assertTrue($response->isValidRisk);
        $this->assertTrue($response->isValidScore);
        $this->assertTrue($response->isDisposable);
        $this->assertFalse($response->hasSuggestion);
        $this->assertNull($response->suggestion);
    }

    /**
     * @throws \SendGridValidation\Exception\CannotValidateEmailException
     */
    public function testThatEmailIsInValidWhenDisposable(): void
    {
        $sendGridRes = [[
            'verdict' => self::RISKY,
            'score' => 1,
            'checks' => [
                'domain' => [
                    'is_suspected_disposable_address' => true,
                ],
            ],
        ]];

        $this->sendGridService->expects($this->once())
            ->method('handleValidation')
            ->with($this->identicalTo(self::EMAIL))
            ->willReturn($sendGridRes);

        $validation = new EmailValidation($this->sendGridService, true, false);

        $response = $validation->validate(self::EMAIL);

        $this->assertInstanceOf(EmailValidationDto::class, $response);
        $this->assertFalse($response->isValid);
        $this->assertTrue($response->isValidRisk);
        $this->assertTrue($response->isValidScore);
        $this->assertTrue($response->isDisposable);
        $this->assertFalse($response->hasSuggestion);
        $this->assertNull($response->suggestion);
    }

    protected function setup(): void
    {
        $this->sendGridService = $this->createMock(SendGridApiRepository::class);
    }
}
