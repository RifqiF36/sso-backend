<?php

return [
    'asset' => [
        'name' => 'Asset Management',
        'url' => env('APP_ASSET_URL', 'http://127.0.0.1:8000'),
        'roles' => ['asset_admin', 'asset_viewer'],
        'icon' => 'assets.svg',
    ],
    'maintenance' => [
        'name' => 'Service Desk',
        'url' => env('APP_SERVICE_DESK_URL', 'http://127.0.0.1:8000'),
        'roles' => ['service_admin', 'service_agent'],
        'icon' => 'maintenance.svg',
    ],
    'change' => [
        'name' => 'Change Management',
        'url' => env('APP_CHANGE_URL', 'http://127.0.0.1:8000'),
        'roles' => ['change_admin', 'change_approver'],
        'icon' => 'change.svg',
    ],
];

