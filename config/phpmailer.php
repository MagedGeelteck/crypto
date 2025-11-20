<?php

return [
    /*
    |--------------------------------------------------------------------------
    | PHPMailer Configuration
    |--------------------------------------------------------------------------
    |
    | These settings are used by any custom mailer implementation that uses
    | PHPMailer directly. They default to the standard MAIL_* env values so
    | you can continue to use Laravel's mail config or override specifically
    | for PHPMailer here.
    |
    */

    'enabled' => env('PHPMAILER_ENABLED', false),

    'host' => env('PHPMAILER_HOST', env('MAIL_HOST', '127.0.0.1')),

    'port' => env('PHPMAILER_PORT', env('MAIL_PORT', 25)),

    'username' => env('PHPMAILER_USERNAME', env('MAIL_USERNAME')),

    'password' => env('PHPMAILER_PASSWORD', env('MAIL_PASSWORD')),

    'encryption' => env('PHPMAILER_ENCRYPTION', env('MAIL_ENCRYPTION', null)),

    'from' => [
        'address' => env('PHPMAILER_FROM_ADDRESS', env('MAIL_FROM_ADDRESS')),
        'name' => env('PHPMAILER_FROM_NAME', env('MAIL_FROM_NAME')),
    ],

    // Additional PHPMailer options you might want to toggle
    'smtp_auth' => env('PHPMAILER_SMTP_AUTH', true),
    'smtp_secure' => env('PHPMAILER_SMTP_SECURE', null), // e.g. 'tls' or 'ssl'
    'smtp_debug' => env('PHPMAILER_SMTP_DEBUG', 0),
];
