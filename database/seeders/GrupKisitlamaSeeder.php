<?php

namespace Database\Seeders;

use App\Models\GrupKisitlama;
use App\Models\OgrenciGrubu;
use App\Models\ZamanDilim;
use Illuminate\Database\Seeder;

class GrupKisitlamaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gruplar = OgrenciGrubu::whereNotNull('ust_grup_id')->get(); // Sadece alt gruplar için
        $zamanDilimleri = ZamanDilim::all();

        foreach ($gruplar as $grup) {
            // Her grup için bazı zaman dilimlerinde müsaitlik durumu tanımla
            $zamanDilimleriSayisi = $zamanDilimleri->count();

            // Rastgele 5-10 zaman dilimi seç ve müsait değil olarak işaretle
            // (Örn: grup o zaman diliminde başka bir ders alıyor olabilir)
            $musaitDegil = rand(5, 10);
            $musaitDegilZamanlar = $zamanDilimleri->random($musaitDegil);

            foreach ($musaitDegilZamanlar as $zaman) {
                GrupKisitlama::create([
                    'ogrenci_grup_id' => $grup->id,
                    'zaman_dilimi_id' => $zaman->id,
                    'musait_mi' => false,
                ]);
            }

            // Rastgele 20-30 zaman dilimi seç ve müsait olarak işaretle
            $musaitSayisi = rand(20, 30);
            $musaitZamanlar = $zamanDilimleri
                ->whereNotIn('id', $musaitDegilZamanlar->pluck('id'))
                ->random(min($musaitSayisi, $zamanDilimleriSayisi - $musaitDegil));

            foreach ($musaitZamanlar as $zaman) {
                GrupKisitlama::create([
                    'ogrenci_grup_id' => $grup->id,
                    'zaman_dilimi_id' => $zaman->id,
                    'musait_mi' => true,
                ]);
            }
        }
    }
}
