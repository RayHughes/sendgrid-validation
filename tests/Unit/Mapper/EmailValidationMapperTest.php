<?php

declare(strict_types=1);

namespace SendGridValidation\Tests\Unit\Mapper;

use PHPUnit\Framework\TestCase;
use SendGridValidation\Dto\EmailValidationDto;
use SendGridValidation\Mapper\EmailValidationMapper;

class EmailValidationMapperTest extends TestCase
{
    private const SUGGESTION = 'suggestion';

    public function testThatItMapsInvalid(): void
    {
        $mapper = new EmailValidationMapper();

        $result = $mapper->map(false, true, false, true);

        $this->assertInstanceOf(EmailValidationDto::class, $result);
        $this->assertFalse($result->isValid);
    }

    public function testThatItMapsSuggestion(): void
    {
        $mapper = new EmailValidationMapper();

        $result = $mapper->map(
            false,
            true,
            false,
            true,
            self::SUGGESTION
        );

        $this->assertInstanceOf(EmailValidationDto::class, $result);
        $this->assertTrue($result->hasSuggestion);
    }

    public function testThatItMapsInValidWhenDisposable(): void
    {
        $mapper = new EmailValidationMapper();

        $result = $mapper->map(true, true, true, false);

        $this->assertInstanceOf(EmailValidationDto::class, $result);
        $this->assertFalse($result->isValid);
    }

    public function testThatItMapsValid(): void
    {
        $mapper = new EmailValidationMapper();

        $result = $mapper->map(true, true, false, true);

        $this->assertInstanceOf(EmailValidationDto::class, $result);
        $this->assertTrue($result->isValid);
    }
}
