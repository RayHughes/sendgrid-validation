# sendgrid-validation
[![Packagist](https://img.shields.io/packagist/v/rayhughes/sendgrid-validation.svg)](https://packagist.org/packages/rayhughes/sendgrid-validation)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.4-8892BF.svg)](https://php.net/)
[![Licensed under the MIT License](https://img.shields.io/badge/License-MIT-blue.svg)](https://github.com/RayHughes/moneypot-api/blob/master/LICENSE)

A PHP library to validate email addresses using the Twilio SendGrid [Email Validation API](https://docs.sendgrid.com/ui/managing-contacts/email-address-validation).

## Prerequisites
- Twilio SendGrid [Email Validation API Key](https://docs.sendgrid.com/ui/managing-contacts/email-address-validation#generating-your-email-validation-api-key)
- PHP 7.4 or greater
- [Composer](http://getcomposer.org/)

## Installation

```bash
composer require rayhughes/sendgrid-validation
```

## Usage

### Basic
`EmailValidation()` initialized with default thresholds.

```php
use SendGridValidation\EmailValidation;
use SendGridValidation\Repository\SendGridApiRepository;

$validation = new EmailValidation(new SendGridApiRepository('api-key'));

$emailValid = $validation->validate('email@example.com'));

echo $emailValid->isValid; // true
echo $emailValid->isValidRisk; // true
echo $emailValid->isValidScore; // true
echo $emailValid->isDisposable; // false
echo $emailValid->hasSuggestion; //false
echo $emailValid->suggestion; // null
````
`EmailValidation()->validate()` returns an instance of `EmailValidationDto()`

#### EmailValidationDto()

- `$isValid` - Calculated `true` if validation result meets specified thresholds.
- `$isValidRisk` - Calculated `true` specified risk criteria
- `$isValidScore` - Calculated `true` if within minimum score threshold.
- `$isDisposable` - Calculated `true` if an email is considered to be disposable.
- `$hasSuggestion` - Calculated `true` if a suggestion is available.
- `$suggestion` - Calculated email suggestion if available. 

```php
class EmailValidationDto
{
    public bool $isValid = false;

    public bool $isValidRisk = false;

    public bool $isValidScore = false;

    public bool $isDisposable = false;

    public bool $hasSuggestion = false;

    public ?string $suggestion = null;
}
```

### Advanced
`EmailValidation()` can be initialized with optional parameters to validate against developer specified thresholds.

- `$allowRisky` - When `true`, considers risky emails `valid` if other conditions are met.
- `$allowDisposable`- When `true`, considers disposable emails `valid` if other conditions are met.
- `$checkValidScore` - When `true`, checks SendGrid `valid` emails against the minimum score threshold.
- `$minSCore` - Default `0.30`. considers emails `invalid` if the minimum score threshold is not met.

```php
use SendGridValidation\EmailValidation;
use SendGridValidation\Repository\SendGridApiRepository;

$validation = new EmailValidation(
    new SendGridApiRepository($apiKey),
    true, // bool $allowRisky = true,
    true, // bool $allowDisposable = true,
    true, // bool $checkValidScore = false,
    EmailValidation::MIN_SCORE // float $minScore = self::MIN_SCORE (0.30)
);

$emailValid = $validation->validate($email);
```
