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
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI'),
    ],

    'paypal_sandbox' => [    
        'client_id' => env('PAYPALSANDBOX_CLIENT_ID'),  
        'client_secret' => env('PAYPALSANDBOX_CLIENT_SECRET'),  
        'redirect' => env('PAYPALSANDBOX_REDIRECT_URI'),
        'base_auth_endpoint' => env('PAYPALSANDBOX_BASE_AUTH_ENDPOINT'),
        'token_endpoint' => env('PAYPALSANDBOX_TOKEN_ENDPOINT'),
        'user_info_endpoint' => env('PAYPALSANDBOX_USER_INFO_ENDPOINT'),
    ],

    'paypal' => [    
        'client_id' => env('PAYPAL_CLIENT_ID'),  
        'client_secret' => env('PAYPAL_CLIENT_SECRET'),  
        'redirect' => env('PAYPAL_REDIRECT_URI') ,
        'base_auth_endpoint' => env('PAYPAL_BASE_AUTH_ENDPOINT'),
        'token_endpoint' => env('PAYPAL_TOKEN_ENDPOINT'),
        'user_info_endpoint' => env('PAYPAL_USER_INFO_ENDPOINT'),
    ],

];
