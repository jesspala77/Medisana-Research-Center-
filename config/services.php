<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS, Microsoft, and more. This file provides the
    | conventional location for packages to locate service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'microsoft' => [
        'client_id' => env('MICROSOFT_CLIENT_ID'),
        'client_secret' => env('MICROSOFT_CLIENT_SECRET'),
        'redirect' => env('MICROSOFT_REDIRECT_URI'),

        'tenant' => env('MICROSOFT_TENANT_ID', 'common'),

        'scopes' => array_values(array_filter(preg_split(
            '/\s+/',
            (string) env(
                'MICROSOFT_SCOPES',
                'openid profile email offline_access User.Read Mail.Send Calendars.ReadWrite'
            )
        ))),

        'sender_email' => env('MICROSOFT_SENDER_EMAIL', env('MAIL_FROM_ADDRESS')),
        'sender_phone' => env('MICROSOFT_SENDER_PHONE', '305-490-5407'),
        'sender_website' => env('MICROSOFT_SENDER_WEBSITE', 'globalsynergiagroup.com'),
    ],

];
