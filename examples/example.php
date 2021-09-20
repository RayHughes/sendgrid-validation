<?php

require '../vendor/autoload.php';

use SendGridValidation\EmailValidation;
use SendGridValidation\Service\SendGridService;

$apiKey = 'api-key';
$email = 'email@example.com';

/**
 * Basic Usage
 */
$validation = new EmailValidation(new SendGridService($apiKey));

print_r($validation->validate($email));

/**
 * Advanced Usage
 */
$validation = new EmailValidation(
    new SendGridService($apiKey), // SendGrid service initialization
    true, // Consider risky valid (checks against minScore)
    true, // Consider disposable emails valid
    true, // Checks minScore on valid emails
    EmailValidation::MIN_SCORE //Score threshold to consider valid. Float 0 - 1
);

print_r($validation->validate($email));
