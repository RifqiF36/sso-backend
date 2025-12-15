<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Dinas;
use App\Models\Unitkerja;
use App\Models\Tenant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get tenant
        $tenant = Tenant::firstOrCreate(
            ['kode' => 'kota_surabaya'],
            ['nama' => 'Kota Surabaya', 'status' => 'active']
        );

        // Get Dinas and Unit Kerja from previous seeders
        $dinasKominfo = Dinas::where('name', 'Dinas Komunikasi dan Informatika')->first();
        $inspektorat = Dinas::where('name', 'Inspektorat Kota')->first();

        // Get Unit Kerja
        $unitKerjas = [
            'Sekretariat Kota' => Unitkerja::where('name', 'Sekretariat Kota')->first(),
            'Kepala Dinas' => Unitkerja::where('name', 'Kepala Dinas')->first(),
            'Sekretariat Dinas' => Unitkerja::where('name', 'Sekretariat Dinas')->first(),
            'Seksi Pengembangan Sistem' => Unitkerja::where('name', 'Seksi Pengembangan Sistem')->first(),
            'Unit Audit dan Pengawasan' => Unitkerja::where('name', 'Unit Audit dan Pengawasan')->first(),
            'Unit Dukungan Teknis' => Unitkerja::where('name', 'Unit Dukungan Teknis')->first(),
            'Bidang Administrasi' => Unitkerja::where('name', 'Bidang Administrasi')->first(),
        ];

        // Get Roles
        $roles = [
            'admin_kota' => Role::where('name', 'admin_kota')->where('module', 'asset_risk')->first(),
            'kepala_dinas' => Role::where('name', 'kepala_dinas')->where('module', 'asset_risk')->first(),
            'admin_dinas' => Role::where('name', 'admin_dinas')->where('module', 'asset_risk')->first(),
            'kepala_seksi' => Role::where('name', 'kepala_seksi')->where('module', 'asset_risk')->first(),
            'kepala_bidang' => Role::where('name', 'kepala_bidang')->where('module', 'asset_risk')->first(),
            'auditor' => Role::where('name', 'auditor')->where('module', 'asset_risk')->first(),
            'teknisi' => Role::where('name', 'teknisi')->where('module', 'asset_risk')->first(),
            'staff' => Role::where('name', 'staff')->where('module', 'asset_risk')->first(),
        ];

        // Create Users
        $users = [
            [
                'name' => 'Admin Kota',
                'email' => 'admin.kota@example.com',
                'password' => 'password123',
                'nip' => '198501012010011001',
                'jenis_kelamin' => 'laki-laki',
                'role' => 'admin_kota',
                'dinas' => null, // Admin kota tidak terikat dinas tertentu
                'unit_kerja' => 'Sekretariat Kota',
            ],
            [
                'name' => 'Kepala Dinas Kominfo',
                'email' => 'kepala.dinas@example.com',
                'password' => 'password123',
                'nip' => '197503152000031001',
                'jenis_kelamin' => 'laki-laki',
                'role' => 'kepala_dinas',
                'dinas' => $dinasKominfo,
                'unit_kerja' => 'Kepala Dinas',
            ],
            [
                'name' => 'Admin Dinas Kominfo',
                'email' => 'admin.dinas@example.com',
                'password' => 'password123',
                'nip' => '199001012015041001',
                'jenis_kelamin' => 'perempuan',
                'role' => 'admin_dinas',
                'dinas' => $dinasKominfo,
                'unit_kerja' => 'Sekretariat Dinas',
            ],
            [
                'name' => 'Kepala Seksi Pengembangan',
                'email' => 'kepala.seksi@example.com',
                'password' => 'password123',
                'nip' => '198803072010012001',
                'jenis_kelamin' => 'perempuan',
                'role' => 'kepala_seksi',
                'dinas' => $dinasKominfo,
                'unit_kerja' => 'Seksi Pengembangan Sistem',
            ],
            [
                'name' => 'Kepala Bidang Pengembangan',
                'email' => 'kepala.bidang@example.com',
                'password' => 'password123',
                'nip' => '198803072010012001',
                'jenis_kelamin' => 'perempuan',
                'role' => 'kepala_bidang',
                'dinas' => $dinasKominfo,
                'unit_kerja' => 'Seksi Pengembangan Sistem',
            ],
            [
                'name' => 'Auditor Internal',
                'email' => 'auditor@example.com',
                'password' => 'password123',
                'nip' => '198206102008011002',
                'jenis_kelamin' => 'laki-laki',
                'role' => 'auditor',
                'dinas' => $inspektorat,
                'unit_kerja' => 'Unit Audit dan Pengawasan',
            ],
            [
                'name' => 'Teknisi IT',
                'email' => 'teknisi@example.com',
                'password' => 'password123',
                'nip' => '199205152018011001',
                'jenis_kelamin' => 'laki-laki',
                'role' => 'teknisi',
                'dinas' => $dinasKominfo,
                'unit_kerja' => 'Unit Dukungan Teknis',
            ],
            [
                'name' => 'Staff Administrasi',
                'email' => 'staff@example.com',
                'password' => 'password123',
                'nip' => '199508202019032001',
                'jenis_kelamin' => 'perempuan',
                'role' => 'staff',
                'dinas' => $dinasKominfo,
                'unit_kerja' => 'Bidang Administrasi',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make($userData['password']),
                    'nip' => $userData['nip'],
                    'jenis_kelamin' => $userData['jenis_kelamin'],
                    'role_id' => $roles[$userData['role']]->id,
                    'dinas_id' => $userData['dinas'] ? $userData['dinas']->id : null,
                    'unit_kerja_id' => $unitKerjas[$userData['unit_kerja']]->id,
                    'email_verified_at' => now(),
                ]
            );

            // Assign role to user in user_roles table
            DB::table('user_roles')->updateOrInsert(
                [
                    'user_id' => $user->id,
                    'role_id' => $roles[$userData['role']]->id,
                    'tenant_id' => $tenant->tenant_id,
                    'module' => 'asset_risk',
                ],
                [
                    'granted_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                    'deleted_at' => null,
                ]
            );
        }

        $this->command->info('Users seeded successfully!');
    }
}
