<?php

return [
    'mode'    => env('PAYPAL_MODE', 'sandbox'),
    'sandbox' => [
        'client_id'    => env('PAYPAL_CLIENT_ID'),
        'client_secret'=> env('PAYPAL_CLIENT_SECRET'),
        'app_id'       => '',
    ],
    'payment_action' => 'Sale',
    'currency'       => 'PHP',
    'notify_url'     => '',
    'locale'         => 'en_PH',
];
