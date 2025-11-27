<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin_kota',
                'module' => 'asset_risk',
                'description' => 'Administrator Kota',
            ],
            [
                'name' => 'kepala_dinas',
                'module' => 'asset_risk',
                'description' => 'Kepala Dinas',
            ],
            [
                'name' => 'admin_dinas',
                'module' => 'asset_risk',
                'description' => 'Admin Dinas',
            ],
            [
                'name' => 'kepala_seksi',
                'module' => 'asset_risk',
                'description' => 'Kepala Seksi',
            ],
            [
                'name' => 'auditor',
                'module' => 'asset_risk',
                'description' => 'Auditor',
            ],
            [
                'name' => 'teknisi',
                'module' => 'asset_risk',
                'description' => 'Teknisi',
            ],
            [
                'name' => 'staff',
                'module' => 'asset_risk',
                'description' => 'Staff',
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role['name'], 'module' => $role['module']],
                ['description' => $role['description']]
            );
        }

        $this->command->info('Roles seeded successfully!');
    }
}
