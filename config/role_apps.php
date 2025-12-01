<?php

if (!function_exists('sso_role_url')) {
    /**
     * Resolve application dashboard URL with optional local override.
     *
     * @param  string  $localDefault  Default URL for local development.
     * @param  string  $envKey        Base environment key (e.g. APP_ASSET_URL_STAFF).
     * @param  string  $remoteDefault Production fallback URL.
     * @return string
     */
    function sso_role_url(string $localDefault, string $envKey, string $remoteDefault): string
    {
        $preferLocal = env('SSO_USE_LOCAL_APP_URLS', env('APP_ENV', 'production') === 'local');
        if (env($envKey . '_OVERRIDE')) {
            return env($envKey . '_OVERRIDE');
        }

        $localValue = env($envKey . '_LOCAL', $localDefault);
        $remoteValue = env($envKey, $remoteDefault);

        return $preferLocal ? $localValue : $remoteValue;
    }
}

return [
    'apps' => [
        'asset' => [
            'name' => 'Asset Management',
            'url' => env('APP_ASSET_URL', 'http://127.0.0.1:8000/api/v1/auth/sso/direct-login?redirect=/'),
            'roles' => ['staff', 'kepala_seksi', 'kepala seksi', 'verifikator', 'admin', 'auditor', 'diskominfo'],
            'default_route' => '/Dashboard-staff',
            'role_routes' => [
                'kepala_seksi' => '/Dashboard-verifikator',
                'kepala seksi' => '/Dashboard-verifikator',
                'verifikator' => '/Dashboard-verifikator',
                'staff' => '/Dashboard-staff',
                'admin' => '/Dashboard-staff',
                'diskominfo' => '/Dashboard-diskominfo',
                'auditor' => '/dashboard-auditor',
            ],
            'role_urls' => [
                'staff' => sso_role_url('http://localhost:5401/', 'APP_ASSET_URL_STAFF', 'https://dinas-siprima.vercel.app/'),
                'user_dinas' => sso_role_url('http://localhost:5401/', 'APP_ASSET_URL_STAFF', 'https://dinas-siprima.vercel.app/'),
                'dinas' => sso_role_url('http://localhost:5401/', 'APP_ASSET_URL_STAFF', 'https://dinas-siprima.vercel.app/'),
                'admin' => sso_role_url('http://localhost:5401/', 'APP_ASSET_URL_STAFF', 'https://dinas-siprima.vercel.app/'),
                'auditor' => sso_role_url('http://localhost:5404/', 'APP_ASSET_URL_AUDITOR', 'https://auditor-siprima.vercel.app/'),
                'diskominfo' => sso_role_url('http://localhost:5403/', 'APP_ASSET_URL_DISKOMINFO', 'https://diskominfo-siprima.vercel.app/'),
                'kepala_seksi' => sso_role_url('http://localhost:5402/', 'APP_ASSET_URL_KEPALA_SEKSI', 'https://verifikator-siprima.vercel.app/'),
                'kepala seksi' => sso_role_url('http://localhost:5402/', 'APP_ASSET_URL_KEPALA_SEKSI', 'https://verifikator-siprima.vercel.app/'),
                'verifikator' => sso_role_url('http://localhost:5402/', 'APP_ASSET_URL_KEPALA_SEKSI', 'https://verifikator-siprima.vercel.app/'),
            ],
            'description' => 'Aset Management System',
            'icon' => 'siprima.png',
        ],
        'maintenance' => [
            'name' => 'Service Desk',
            'url' => env('APP_SERVICE_DESK_URL', 'http://127.0.0.1:8000'),
            'roles' => ['staff', 'admin_kota', 'admin_dinas', 'kepala_seksi', 'kepala_bidang', 'teknisi'],
            'default_route' => '/',
            'role_urls' => [
                'staff' => env('APP_SERVICE_DESK_URL_STAFF', env('APP_SERVICE_DESK_URL', 'http://127.0.0.1:8000')),
                'admin' => env('APP_SERVICE_DESK_URL_ADMIN', env('APP_SERVICE_DESK_URL', 'http://127.0.0.1:8000')),
                'kepala_seksi' => env('APP_SERVICE_DESK_URL_KEPALA_SEKSI', env('APP_SERVICE_DESK_URL', 'http://127.0.0.1:8000')),
                'kepala seksi' => env('APP_SERVICE_DESK_URL_KEPALA_SEKSI', env('APP_SERVICE_DESK_URL', 'http://127.0.0.1:8000')),
                'kepala_bidang' => env('APP_SERVICE_DESK_URL_KEPALA_BIDANG', env('APP_SERVICE_DESK_URL', 'http://127.0.0.1:8000')),
                'teknisi' => env('APP_SERVICE_DESK_URL_TEKNISI', env('APP_SERVICE_DESK_URL', 'http://127.0.0.1:8000')),
                'diskominfo' => env('APP_SERVICE_DESK_URL_DISKOMINFO', env('APP_SERVICE_DESK_URL', 'http://127.0.0.1:8000')),
            ],
            'description' => 'Service Desk Management',
            'icon' => 'sindra.png',
        ],
        'change' => [
            'name' => 'Change Management',
            'url' => env('APP_CHANGE_URL', 'https://simantic.online/api/sso/callback'),
            'roles' => ['*'],
            'default_route' => '/',
            'role_urls' => [
                'staff' => env('APP_CHANGE_URL_STAFF', 'https://simantic.online/api/sso/callback'),
                'admin' => env('APP_CHANGE_URL_ADMIN', 'https://simantic.online/api/sso/callback'),
                'admin_kota' => env('APP_CHANGE_URL_ADMIN_KOTA', 'https://simantic.online/api/sso/callback'),
                'admin_dinas' => env('APP_CHANGE_URL_ADMIN_DINAS', 'https://simantic.online/api/sso/callback'),
                'kepala_seksi' => env('APP_CHANGE_URL_KEPALA_SEKSI', 'https://simantic.online/api/sso/callback'),
                'kepala_bidang' => env('APP_CHANGE_URL_KEPALA_BIDANG', 'https://simantic.online/api/sso/callback'),
                'kepala_dinas' => env('APP_CHANGE_URL_KEPALA_DINAS', 'https://simantic.online/api/sso/callback'),
                'diskominfo' => env('APP_CHANGE_URL_DISKOMINFO', 'https://simantic.online/api/sso/callback'),
                'auditor' => env('APP_CHANGE_URL_AUDITOR', 'https://simantic.online/api/sso/callback'),
            ],
            'description' => 'Change & Configuration Management',
            'icon' => 'simantic.png',
        ],
    ],
    'dashboards' => [
        'asset' => [
            'staff' => 'dashboard.staff',
            'kepala_seksi' => 'dashboard.kasi',
            'auditor' => 'dashboard.auditor',
            'diskominfo' => 'dashboard.diskominfo',
            'admin' => null,
            'kepala_bidang' => null,
            'kepala_dinas' => null,
            'teknisi' => null,
        ],
        'change' => [
            'staff' => 'dashboard.staff',
            'admin' => 'dashboard.admin',
            'kepala_seksi' => 'dashboard.kasi',
            'kepala_bidang' => 'dashboard.kabid',
            'kepala_dinas' => 'dashboard.kadis',
            'auditor' => 'dashboard.audit',
            'diskominfo' => 'dashboard.diskominfo',
            'teknisi' => null,
        ],
        'service' => [
            'staff' => 'dashboard.staff',
            'admin' => 'dashboard.admin',
            'kepala_seksi' => 'dashboard.kasi',
            'kepala_bidang' => 'dashboard.kabid',
            'teknisi' => 'dashboard.teknisi',
            'diskominfo' => 'dashboard.diskominfo',
            'kepala_dinas' => null,
            'auditor' => null,
        ],
    ],
];
