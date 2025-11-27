<?php

return [
    /**
     * Menu configuration for each role
     * Format: 'role_name' => ['app1', 'app2', ...]
     */
    'role_menu_mapping' => [
        'admin_kota' => ['asset', 'change', 'maintenance'],
        'kepala_dinas' => ['change'],
        'admin_dinas' => ['asset', 'change', 'maintenance'],
        'kepala_bidang' => ['change', 'maintenance'],
        'kepala_seksi' => ['asset', 'change', 'maintenance'],
        'auditor' => ['change', 'asset'],
        'teknisi' => ['maintenance'],
        'staff' => ['asset', 'change', 'maintenance'],
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
