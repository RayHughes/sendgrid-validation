<?php

declare(strict_types=1);

namespace SendGridValidation;

use SendGridValidation\Dto\EmailValidationDto;
use SendGridValidation\Mapper\EmailValidationMapper;
use SendGridValidation\Service\SendGridService;

class EmailValidation
{
    public const MIN_SCORE = 0.30;

    private const RISKY = 'Risky';

    private const INVALID = 'Invalid';

    private SendGridService $sendGridService;

    private bool $allowRisky;

    private bool $allowDisposable;

    private bool $checkValidScore;

    private float $minScore;

    public function __construct(
        SendGridService $sendGridService,
        bool $allowRisky = true,
        bool $allowDisposable = true,
        bool $checkValidScore = false,
        float $minScore = self::MIN_SCORE
    ) {
        $this->sendGridService = $sendGridService;
        $this->allowRisky = $allowRisky;
        $this->allowDisposable = $allowDisposable;
        $this->checkValidScore = $checkValidScore;
        $this->minScore = $minScore;
    }

    /**
     * @throws \SendGridValidation\Exception\CannotValidateEmailException
     */
    public function validate(string $email): EmailValidationDto
    {
        $response = $this->sendGridService->handleValidation($email);

        $validationResult = reset($response);

        return (new EmailValidationMapper())->map(
            $this->isValidRisk($validationResult),
            $this->isValidScore($validationResult),
            $this->isDisposable($validationResult),
            $this->allowDisposable,
            $this->calculateSuggestion($validationResult)
        );
    }

    private function calculateSuggestion(array $validationResult): ?string
    {
        return isset($validationResult['suggestion'])
            ? $validationResult['local'] . '@' . $validationResult['suggestion']
            : null;
    }

    private function isValidRisk(array $validationResult): bool
    {
        switch ($validationResult['verdict']) {
            case self::INVALID:
                return false;
            case self::RISKY:
                return $this->allowRisky;
            default:
                return true;
        }
    }

    private function isValidScore(array $validationResult): bool
    {
        if ($this->checkValidScore || in_array($validationResult['verdict'], [self::RISKY, self::INVALID])) {
            return ($validationResult['score'] >= $this->minScore);
        }

        return true;
    }

    private function isDisposable(array $validationResult): bool
    {
        return !empty($validationResult['checks']['domain']['is_suspected_disposable_address']);
    }
}
