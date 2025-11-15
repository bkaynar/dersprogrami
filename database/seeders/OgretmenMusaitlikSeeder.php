<?php

namespace Database\Seeders;

use App\Models\Ogretmen;
use App\Models\OgretmenMusaitlik;
use App\Models\ZamanDilim;
use Illuminate\Database\Seeder;

class OgretmenMusaitlikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ogretmenler = Ogretmen::all();
        $zamanDilimleri = ZamanDilim::all();

        foreach ($ogretmenler as $ogretmen) {
            // Her öğretmen için rastgele müsaitlik durumları oluştur
            $zamanDilimleriSayisi = $zamanDilimleri->count();
            $musaitSayisi = rand(20, 30); // Her öğretmen 20-30 zaman diliminde müsait
            $musaitDegil = rand(3, 8); // 3-8 zaman diliminde müsait değil
            $tercihEdilen = rand(5, 10); // 5-10 zaman dilimi tercih edilen

            // Rastgele zaman dilimlerini seç ve müsait olarak işaretle
            $musaitZamanlar = $zamanDilimleri->random($musaitSayisi);
            foreach ($musaitZamanlar as $zaman) {
                OgretmenMusaitlik::create([
                    'ogretmen_id' => $ogretmen->id,
                    'zaman_dilimi_id' => $zaman->id,
                    'musaitlik_tipi' => 'musait',
                ]);
            }

            // Bazı zaman dilimlerini müsait değil olarak işaretle
            $musaitOlmayanZamanlar = $zamanDilimleri
                ->whereNotIn('id', $musaitZamanlar->pluck('id'))
                ->random(min($musaitDegil, $zamanDilimleriSayisi - $musaitSayisi));

            foreach ($musaitOlmayanZamanlar as $zaman) {
                OgretmenMusaitlik::create([
                    'ogretmen_id' => $ogretmen->id,
                    'zaman_dilimi_id' => $zaman->id,
                    'musaitlik_tipi' => 'musait_degil',
                ]);
            }

            // Bazı müsait zaman dilimlerinden tercih edilenleri seç
            $tercihEdilenZamanlar = $musaitZamanlar->random(min($tercihEdilen, $musaitSayisi));
            foreach ($tercihEdilenZamanlar as $zaman) {
                // Önce müsait kaydını sil
                OgretmenMusaitlik::where('ogretmen_id', $ogretmen->id)
                    ->where('zaman_dilimi_id', $zaman->id)
                    ->delete();

                // Tercih edilen olarak ekle
                OgretmenMusaitlik::create([
                    'ogretmen_id' => $ogretmen->id,
                    'zaman_dilimi_id' => $zaman->id,
                    'musaitlik_tipi' => 'tercih_edilen',
                ]);
            }
        }
    }
}
