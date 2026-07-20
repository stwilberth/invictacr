<?php

return [
    'client_id' => env('PAYPAL_CLIENT_ID', ''),
    'client_secret' => env('PAYPAL_CLIENT_SECRET', ''),
    'mode' => env('PAYPAL_MODE', 'live'),
    'base_url' => env('PAYPAL_MODE', 'live') === 'live'
        ? 'https://api-m.paypal.com'
        : 'https://api-m.sandbox.paypal.com',
    'currency' => 'USD',
    'brand_name' => 'Invicta Costa Rica',
    'return_url' => env('APP_URL', 'https://invictacostarica.com') . '/paypal/execute',
    'cancel_url' => env('APP_URL', 'https://invictacostarica.com') . '/paypal/cancel',
];
