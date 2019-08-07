<?php

return [
    'clients' => [
        'password' => [
            'client_id' => env('PASSPORT_PASSWORD_CLIENT_ID', 2),
            'client_secret' => env('PASSPORT_PASSWORD_CLIENT_SECRET'),
        ],
    ],
];
