<?php

return [
    /**
     * Menu configuration for each role
     * Format: 'role_name' => ['app1', 'app2', ...]
     */
    'role_menu_mapping' => [
        'admin_kota' => ['asset', 'change', 'maintenance'], // siprima, simantic, sindra
        'kepala_dinas' => ['change'], // simantic
        'kepala_bidang' => ['change'], // simantic
        'admin_dinas' => ['asset', 'change', 'maintenance'], // siprima, simantic, sindra
        'kepala_bidang' => ['change', 'maintenance'], // simantic, sindra
        'kepala_seksi' => ['asset', 'change', 'maintenance'], // siprima, simantic, sindra
        'auditor' => ['change', 'asset'], // simantic, siprima
        'teknisi' => ['maintenance'], // sindra
        'staff' => ['asset', 'change', 'maintenance'], // siprima, simantic, sindra
    ],

    /**
     * Role name variations mapping
     * Maps different role name formats to standardized keys
     */
    'role_aliases' => [
        'kepala seksi' => 'kepala_seksi',
        'kepala_bidang' => 'kepala_bidang',
        'kepala_dinas' => 'kepala_dinas',
        'admin kota' => 'admin_kota',
        'admin_kota' => 'admin_kota',
        'admin dinas' => 'admin_dinas',
        'admin_dinas' => 'admin_dinas',
    ],

    /**
     * Application display names
     */
    'app_names' => [
        'asset' => 'SIPRIMA',
        'change' => 'SIMANTIC',
        'maintenance' => 'SINDRA',
    ],
];
