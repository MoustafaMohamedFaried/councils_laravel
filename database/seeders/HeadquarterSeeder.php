<?php

namespace Database\Seeders;

use App\Models\Headquarter;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HeadquarterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $headquarters = [
            [
                'id' => 6,
                'code' => 'hq_6',
                'en_name' => 'Briyda',
                'ar_name' => 'بريدة',
                'address' => 'القصيم - بريدة',
            ],
            [
                'id' => 7,
                'code' => 'hq_7',
                'en_name' => 'Unizaha',
                'ar_name' => 'عنيزة',
                'address' => 'القصيم - عنيزة',
            ],
        ];

        foreach ($headquarters as $headquarter) {
            Headquarter::create([
                'id' => $headquarter['id'],
                'code' => $headquarter['code'],
                'en_name' => $headquarter['en_name'],
                'ar_name' => $headquarter['ar_name'],
                'address' => $headquarter['address'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
