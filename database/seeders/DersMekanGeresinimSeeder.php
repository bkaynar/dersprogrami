<?php

namespace Database\Seeders;

use App\Models\Ders;
use App\Models\DersMekanGereksinimi;
use Illuminate\Database\Seeder;

class DersMekanGeresinimSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Programlama dersleri için laboratuvar zorunlu
        $programlamaDersleri = Ders::whereIn('ders_kodu', ['BM101', 'BM201', 'BM203', 'BM301', 'BM401', 'BM402'])->get();
        foreach ($programlamaDersleri as $ders) {
            DersMekanGereksinimi::create([
                'ders_id' => $ders->id,
                'mekan_tipi' => 'laboratuvar',
                'gereksinim_tipi' => 'zorunlu',
            ]);
        }

        // Matematik ve teorik dersler için derslik
        $teorikDersler = Ders::whereIn('ders_kodu', ['BM102', 'BM103', 'BM202', 'BM302', 'BM303', 'BM403'])->get();
        foreach ($teorikDersler as $ders) {
            DersMekanGereksinimi::create([
                'ders_id' => $ders->id,
                'mekan_tipi' => 'derslik',
                'gereksinim_tipi' => 'zorunlu',
            ]);
        }

        // Elektrik dersleri için laboratuvar zorunlu
        $elektrikDersleri = Ders::whereIn('ders_kodu', ['EE101', 'EE201', 'EE301'])->get();
        foreach ($elektrikDersleri as $ders) {
            DersMekanGereksinimi::create([
                'ders_id' => $ders->id,
                'mekan_tipi' => 'laboratuvar',
                'gereksinim_tipi' => 'zorunlu',
            ]);
        }

        // Bazı dersler için konferans salonu tercihi
        $konferansDersleri = Ders::whereIn('ders_kodu', ['BM401', 'BM403'])->get();
        foreach ($konferansDersleri as $ders) {
            DersMekanGereksinimi::create([
                'ders_id' => $ders->id,
                'mekan_tipi' => 'konferans_salonu',
                'gereksinim_tipi' => 'olabilir',
            ]);
        }
    }
}
