<?php

return [
    'asset' => [
        'name' => 'Asset Management',
        'url' => env('APP_ASSET_URL', 'http://127.0.0.1:8000/api/v1/auth/sso/direct-login?redirect=/'),
        'roles' => [],
        'description' => 'Aset Management System',
        'icon' => 'siprima.png',
    ],
    'maintenance' => [
        'name' => 'Service Desk',
        'url' => env('APP_SERVICE_DESK_URL', 'http://127.0.0.1:8000'),
        'roles' => [],
        'description' => 'Service Desk Management',
        'icon' => 'sindra.png',
    ],
    'change' => [
        'name' => 'Change Management',
        'url' => env('APP_CHANGE_URL', 'http://127.0.0.1:8000'),
        'roles' => [],
        'description' => 'Change & Configuration Management',
        'icon' => 'simantic.png',
    ],
];

