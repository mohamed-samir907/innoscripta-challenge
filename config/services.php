<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
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

    'news_api' => [
        'base_url' => env('NEWS_API_BASE_URL', 'https://newsapi.org'),
        'key' => env('NEWS_API_KEY'),
    ],

    'theguardian' => [
        'base_url' => env('THE_GUARDIAN_BASE_URL', 'https://content.guardianapis.com'),
        'key' => env('THE_GUARDIAN_KEY'),
    ],

    'nytimes' => [
        'base_url' => env('NYT_BASE_URL', 'https://api.nytimes.com'),
        'key' => env('NYT_API_KEY'),
    ],
];
