<?php

namespace Database\Seeders;

use App\Models\Mekan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MekanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mekanlar = [
            // Derslikler
            ['isim' => 'A101 Derslik', 'kapasite' => 40, 'mekan_tipi' => 'derslik'],
            ['isim' => 'A102 Derslik', 'kapasite' => 40, 'mekan_tipi' => 'derslik'],
            ['isim' => 'A103 Derslik', 'kapasite' => 35, 'mekan_tipi' => 'derslik'],
            ['isim' => 'A201 Derslik', 'kapasite' => 50, 'mekan_tipi' => 'derslik'],
            ['isim' => 'A202 Derslik', 'kapasite' => 45, 'mekan_tipi' => 'derslik'],
            ['isim' => 'B101 Derslik', 'kapasite' => 30, 'mekan_tipi' => 'derslik'],
            ['isim' => 'B102 Derslik', 'kapasite' => 30, 'mekan_tipi' => 'derslik'],

            // Laboratuvarlar
            ['isim' => 'Bilgisayar Laboratuvarı 1', 'kapasite' => 30, 'mekan_tipi' => 'laboratuvar'],
            ['isim' => 'Bilgisayar Laboratuvarı 2', 'kapasite' => 30, 'mekan_tipi' => 'laboratuvar'],
            ['isim' => 'Fizik Laboratuvarı', 'kapasite' => 25, 'mekan_tipi' => 'laboratuvar'],
            ['isim' => 'Kimya Laboratuvarı', 'kapasite' => 25, 'mekan_tipi' => 'laboratuvar'],
            ['isim' => 'Elektronik Laboratuvarı', 'kapasite' => 20, 'mekan_tipi' => 'laboratuvar'],

            // Konferans Salonları
            ['isim' => 'Ana Konferans Salonu', 'kapasite' => 200, 'mekan_tipi' => 'konferans_salonu'],
            ['isim' => 'Küçük Konferans Salonu', 'kapasite' => 100, 'mekan_tipi' => 'konferans_salonu'],
            ['isim' => 'Seminer Salonu', 'kapasite' => 80, 'mekan_tipi' => 'konferans_salonu'],
        ];

        foreach ($mekanlar as $mekan) {
            Mekan::create($mekan);
        }
    }
}
