<?php

namespace Database\Seeders;

use App\Models\Unitkerja;
use Illuminate\Database\Seeder;

class UnitKerjaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $unitKerjaList = [
            'Sekretariat Kota',
            'Kepala Dinas',
            'Sekretariat Dinas',
            'Seksi Pengembangan Sistem',
            'Seksi Infrastruktur TIK',
            'Seksi Aplikasi dan Data',
            'Bidang E-Government',
            'Bidang Layanan Informasi',
            'Unit Audit dan Pengawasan',
            'Unit Dukungan Teknis',
            'Bidang Administrasi',
            'Bidang Keuangan',
        ];

        foreach ($unitKerjaList as $unitKerjaName) {
            Unitkerja::firstOrCreate(['name' => $unitKerjaName]);
        }

        $this->command->info('Unit Kerja seeded successfully!');
    }
}
