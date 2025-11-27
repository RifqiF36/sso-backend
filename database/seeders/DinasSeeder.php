<?php

namespace Database\Seeders;

use App\Models\Dinas;
use Illuminate\Database\Seeder;

class DinasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dinasList = [
            [
                'name' => 'Dinas Komunikasi dan Informatika',
                'address' => 'Jl. Jimerto No. 25-27, Surabaya',
            ],
            [
                'name' => 'Dinas Kesehatan',
                'address' => 'Jl. Jambangan Kebon Agung No. 2, Surabaya',
            ],
            [
                'name' => 'Dinas Pendidikan',
                'address' => 'Jl. Jagir Wonokromo No. 354, Surabaya',
            ],
            [
                'name' => 'Dinas Perhubungan',
                'address' => 'Jl. Gubeng Kertajaya No. 1, Surabaya',
            ],
            [
                'name' => 'Inspektorat Kota',
                'address' => 'Jl. Taman Jayengrono No. 5, Surabaya',
            ],
        ];

        foreach ($dinasList as $dinas) {
            Dinas::firstOrCreate(
                ['name' => $dinas['name']],
                ['address' => $dinas['address']]
            );
        }

        $this->command->info('Dinas seeded successfully!');
    }
}
