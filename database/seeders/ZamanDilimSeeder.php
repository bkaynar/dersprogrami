<?php

namespace Database\Seeders;

use App\Models\ZamanDilim;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ZamanDilimSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gunler = ['pazartesi', 'sali', 'carsamba', 'persembe', 'cuma'];

        // Standart ders saatleri
        $saatler = [
            ['baslangic_saati' => '09:00', 'bitis_saati' => '09:50'],
            ['baslangic_saati' => '10:00', 'bitis_saati' => '10:50'],
            ['baslangic_saati' => '11:00', 'bitis_saati' => '11:50'],
            ['baslangic_saati' => '13:00', 'bitis_saati' => '13:50'],
            ['baslangic_saati' => '14:00', 'bitis_saati' => '14:50'],
            ['baslangic_saati' => '15:00', 'bitis_saati' => '15:50'],
            ['baslangic_saati' => '16:00', 'bitis_saati' => '16:50'],
        ];

        // Her gün için tüm ders saatlerini oluştur
        foreach ($gunler as $gun) {
            foreach ($saatler as $saat) {
                ZamanDilim::create([
                    'haftanin_gunu' => $gun,
                    'baslangic_saati' => $saat['baslangic_saati'],
                    'bitis_saati' => $saat['bitis_saati'],
                ]);
            }
        }

        // Cumartesi için yarım gün
        $cumartesiSaatler = [
            ['baslangic_saati' => '09:00', 'bitis_saati' => '09:50'],
            ['baslangic_saati' => '10:00', 'bitis_saati' => '10:50'],
            ['baslangic_saati' => '11:00', 'bitis_saati' => '11:50'],
            ['baslangic_saati' => '13:00', 'bitis_saati' => '13:50'],
        ];

        foreach ($cumartesiSaatler as $saat) {
            ZamanDilim::create([
                'haftanin_gunu' => 'cumartesi',
                'baslangic_saati' => $saat['baslangic_saati'],
                'bitis_saati' => $saat['bitis_saati'],
            ]);
        }
    }
}
