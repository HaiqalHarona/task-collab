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
    
    'github' => [
        'client_id' => env('Iv23liEsufrnO0JW88qR'),
        'client_secret' => env('eb1f24b34d618cf98fb27cf22251dc4f8652cc8a'),
        'redirect_uri' => env('http://localhost:8000/auth/github/callback'),
    ],

    'google' => [
        'client_id' => env('1089032762924-fkoorv3tl0pi30jp11jo42jd9e5hqk9u.apps.googleusercontent.com'),
        'client_secret' => env('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'),
        'redirect_uri' => env('http://localhost:8000/auth/google/callback'),
    ],


];
