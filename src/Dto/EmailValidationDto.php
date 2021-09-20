<?php

declare(strict_types=1);

namespace SendGridValidation\Dto;

class EmailValidationDto
{
    public bool $isValid = false;

    public bool $isValidRisk = false;

    public bool $isValidScore = false;

    public bool $isDisposable = false;

    public bool $hasSuggestion = false;

    public ?string $suggestion = null;
}
