<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use App\Models\SsoProvider;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class IamQuickSeed extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::firstOrCreate(
            ['kode' => 'diskominfo'],
            ['nama' => 'Diskominfo Kota', 'status' => 'active']
        );

        $user = User::updateOrCreate(
            ['email' => 'admin_kota@sso'],
            [
                'name' => 'Admin Kota',
                'password' => Hash::make('AdminKota@123'),
            ]
        );
        $user->forceFill(['email_verified_at' => now()])->save();
        $user->profile()->updateOrCreate(
            [],
            [
                'nip' => '110920479999',
                'gender' => 'laki_laki',
                'unit_kerja' => 'Admin Kota Surabaya',
                'asal_dinas' => 'Diskominfo Kota Surabaya',
            ]
        );

        $adminRole = Role::firstOrCreate(
            ['name' => 'admin_kota', 'module' => 'asset_risk'],
            ['description' => 'Administrator Kota']
        );

        DB::table('user_roles')->updateOrInsert(
            [
                'user_id' => $user->id,
                'role_id' => $adminRole->role_id,
                'tenant_id' => $tenant->tenant_id,
                'module' => $adminRole->module,
            ],
            [
                'granted_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ]
        );

        $ssoAccounts = [
            [
                'user_id' => 1,
                'email' => 'staff@sso',
                'name' => 'Staff',
                'role' => 'staff',
                'password' => '12345',
                'profile' => [
                    'nip' => '110920471983',
                    'gender' => 'laki_laki',
                    'unit_kerja' => 'Bidang Pencegahan Penyakit',
                    'asal_dinas' => 'Dinas Kesehatan Provinsi Jawa Timur',
                ],
            ],
            [
                'user_id' => 2,
                'email' => 'admin@sso',
                'name' => 'Admin',
                'role' => 'admin',
                'password' => '12345',
                'profile' => [
                    'nip' => '110920470001',
                    'gender' => 'laki_laki',
                    'unit_kerja' => 'Sekretariat Diskominfo',
                    'asal_dinas' => 'Dinas Komunikasi dan Informatika Surabaya',
                ],
            ],
            [
                'user_id' => 3,
                'email' => 'kepala_seksi@sso',
                'name' => 'Kepala Seksi',
                'role' => 'kepala_seksi',
                'password' => '12345',
                'profile' => [
                    'nip' => '110920470002',
                    'gender' => 'perempuan',
                    'unit_kerja' => 'Seksi Infrastruktur',
                    'asal_dinas' => 'Dinas Kominfo Kota Surabaya',
                ],
            ],
            [
                'user_id' => 4,
                'email' => 'kepala_bidang@sso',
                'name' => 'Kepala Bidang',
                'role' => 'kepala_bidang',
                'password' => '12345',
                'profile' => [
                    'nip' => '110920470003',
                    'gender' => 'laki_laki',
                    'unit_kerja' => 'Bidang Operasional',
                    'asal_dinas' => 'Dinas Kominfo Kota Surabaya',
                ],
            ],
            [
                'user_id' => 5,
                'email' => 'teknisi@sso',
                'name' => 'Teknisi',
                'role' => 'teknisi',
                'password' => '12345',
                'profile' => [
                    'nip' => '110920470004',
                    'gender' => 'laki_laki',
                    'unit_kerja' => 'Unit Dukungan Teknis',
                    'asal_dinas' => 'UPT Pusat Data Surabaya',
                ],
            ],
            [
                'user_id' => 6,
                'email' => 'kepala_dinas@sso',
                'name' => 'Kepala Dinas',
                'role' => 'kepala_dinas',
                'password' => '12345',
                'profile' => [
                    'nip' => '110920470005',
                    'gender' => 'perempuan',
                    'unit_kerja' => 'Kepala Dinas Kominfo',
                    'asal_dinas' => 'Dinas Kominfo Provinsi Jawa Timur',
                ],
            ],
            [
                'user_id' => 7,
                'email' => 'diskominfo@sso',
                'name' => 'Diskominfo',
                'role' => 'diskominfo',
                'password' => '12345',
                'profile' => [
                    'nip' => '110920470006',
                    'gender' => 'laki_laki',
                    'unit_kerja' => 'Tim Koordinasi Diskominfo',
                    'asal_dinas' => 'Dinas Kominfo Kota Surabaya',
                ],
            ],
            [
                'user_id' => 8,
                'email' => 'auditor@sso',
                'name' => 'Auditor',
                'role' => 'auditor',
                'password' => '12345',
                'profile' => [
                    'nip' => '110920470007',
                    'gender' => 'perempuan',
                    'unit_kerja' => 'Unit Audit Internal',
                    'asal_dinas' => 'Inspektorat Kota Surabaya',
                ],
            ],
        ];

        $roleModule = 'asset_risk';
        $roleCache = [];

        foreach ($ssoAccounts as $account) {
            $roleName = $account['role'];
            if (!isset($roleCache[$roleName])) {
                $roleCache[$roleName] = Role::firstOrCreate(
                    ['name' => $roleName, 'module' => $roleModule],
                    ['description' => ucwords(str_replace('_', ' ', $roleName))]
                );
            }

            $accountUser = User::updateOrCreate(
                ['email' => $account['email']],
                [
                    'name' => $account['name'],
                    'password' => Hash::make($account['password']),
                ]
            );

            $accountUser->forceFill(['email_verified_at' => now()])->save();
            $accountUser->profile()->updateOrCreate(
                [],
                [
                    'nip' => $account['profile']['nip'] ?? null,
                    'gender' => $account['profile']['gender'] ?? null,
                    'unit_kerja' => $account['profile']['unit_kerja'] ?? null,
                    'asal_dinas' => $account['profile']['asal_dinas'] ?? null,
                ]
            );

            DB::table('user_roles')->updateOrInsert(
                [
                    'user_id' => $accountUser->id,
                    'role_id' => $roleCache[$roleName]->role_id,
                    'tenant_id' => $tenant->tenant_id,
                    'module' => $roleCache[$roleName]->module,
                ],
                [
                    'granted_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                    'deleted_at' => null,
                ]
            );
        }

        $callback = rtrim(config('app.url', 'http://127.0.0.1:9000'), '/') . '/api/v1/auth/sso/callback';

        SsoProvider::firstOrCreate(
            ['name' => 'default'],
            [
                'authorize_url' => config('services.sso.authorize_url'),
                'token_url' => config('services.sso.token_url'),
                'userinfo_url' => config('services.sso.userinfo_url'),
                'redirect_uri' => $callback,
                'client_id' => config('services.sso.client_id'),
                'client_secret' => config('services.sso.client_secret'),
                'scopes' => ['openid', 'profile', 'email'],
                'enabled' => true,
            ]
        );
    }
}

