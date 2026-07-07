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

    'deepseek' => [
        'key' => env('DEEPSEEK_API_KEY'),
    ],

    'google' => [
        'service_account_key_path' => env('GOOGLE_SERVICE_ACCOUNT_KEY_PATH'),
        'analytics_property_id' => env('GOOGLE_ANALYTICS_PROPERTY_ID'),
        'ads_customer_id' => env('GOOGLE_ADS_CUSTOMER_ID'),
        'ads_developer_token' => env('GOOGLE_ADS_DEVELOPER_TOKEN'),
        'search_console_site_url' => env('GOOGLE_SEARCH_CONSOLE_SITE_URL'),
    ],

    'facebook' => [
        'access_token' => env('META_ACCESS_TOKEN'),
        'page_id' => env('META_PAGE_ID'),
        'page_name' => env('META_PAGE_NAME'),
    ],

    'github' => [
        'token' => env('GITHUB_TOKEN'),
        'owner' => env('GITHUB_OWNER'),
        'repo' => env('GITHUB_REPO'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OATH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

];
