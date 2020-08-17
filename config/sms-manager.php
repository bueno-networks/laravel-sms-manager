<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Sms Client
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default sms client that should be used
    | by the package.
    |
    */

    'default' => env('SMS_DRIVER', 'allcance'),

    /*
    |--------------------------------------------------------------------------
    | Sms Clients
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many sms "clients" as you wish.
    |
    | Supported Clients: "allcance"
    |
    */

    'clients' => [

        'allcance' => [
            'user' => env('ALLCANCE_USER'),
            'password' => env('ALLCANCE_PASSWORD'),
        ],
    ]
];
