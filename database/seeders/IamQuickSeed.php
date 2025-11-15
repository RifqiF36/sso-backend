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

        $user = User::firstOrCreate(
            ['email' => 'asset_admin@asetrisk'],
            ['name' => 'Asset Admin', 'password' => Hash::make('S1pr!ma123')]
        );

        $roles = collect([
            ['name' => 'asset_admin', 'module' => 'asset_risk', 'description' => 'Asset administrator'],
            ['name' => 'service_admin', 'module' => 'service_desk', 'description' => 'Service desk administrator'],
            ['name' => 'change_admin', 'module' => 'change_cfg', 'description' => 'Change administrator'],
        ])->map(fn($data) => Role::firstOrCreate(
            ['name' => $data['name'], 'module' => $data['module']],
            ['description' => $data['description']]
        ));

        foreach ($roles as $role) {
            DB::table('user_roles')->updateOrInsert(
                [
                    'user_id' => $user->id,
                    'role_id' => $role->role_id,
                    'tenant_id' => $tenant->tenant_id,
                    'module' => $role->module,
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

