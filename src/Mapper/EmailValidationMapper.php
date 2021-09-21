<?php

declare(strict_types=1);

namespace SendGridValidation\Mapper;

use SendGridValidation\Dto\EmailValidationDto;

class EmailValidationMapper
{
    public function map(
        bool $isValidRisk,
        bool $isValidScore,
        bool $isDisposable,
        bool $allowDisposable,
        ?string $suggestion = null
    ): EmailValidationDto {
        $emailValidationDto = new EmailValidationDto();

        $emailValidationDto->isValid = !(!$allowDisposable && $isDisposable)
            && $isValidRisk
            && $isValidScore;

        $emailValidationDto->isValidRisk = $isValidRisk;
        $emailValidationDto->isValidScore = $isValidScore;
        $emailValidationDto->isDisposable = $isDisposable;
        $emailValidationDto->hasSuggestion = !is_null($suggestion);
        $emailValidationDto->suggestion = $suggestion;

        return $emailValidationDto;
    }
}
