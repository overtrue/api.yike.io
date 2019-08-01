<?php

return [
    'clients' => [
        'password' => [
            'client_id' => env('PASSPORT_PASSWORD_CLIENT_ID', 2),
            'client_secret' => env('PASSPORT_PASSWORD_CLIENT_SECRET'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Encryption Keys
    |--------------------------------------------------------------------------
    |
    | Passport uses encryption keys while generating secure access tokens for
    | your application. By default, the keys are stored as local files but
    | can be set via environment variables when that is more convenient.
    |
    */

    'private_key' => env('PASSPORT_PRIVATE_KEY'),

    'public_key' => env('PASSPORT_PUBLIC_KEY'),
];
