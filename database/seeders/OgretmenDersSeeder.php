<?php

namespace Database\Seeders;

use App\Models\Ders;
use App\Models\Ogretmen;
use App\Models\OgretmenDers;
use Illuminate\Database\Seeder;

class OgretmenDersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Prof. Dr. Ahmet Yılmaz - Yapay Zeka ve Makine Öğrenmesi
        $ogretmen1 = Ogretmen::where('email', 'ahmet.yilmaz@universite.edu.tr')->first();
        $dersler1 = Ders::whereIn('ders_kodu', ['BM401', 'BM402'])->get();
        foreach ($dersler1 as $ders) {
            OgretmenDers::create([
                'ogretmen_id' => $ogretmen1->id,
                'ders_id' => $ders->id,
            ]);
        }

        // Doç. Dr. Ayşe Demir - Veritabanı ve Yazılım Mühendisliği
        $ogretmen2 = Ogretmen::where('email', 'ayse.demir@universite.edu.tr')->first();
        $dersler2 = Ders::whereIn('ders_kodu', ['BM202', 'BM403'])->get();
        foreach ($dersler2 as $ders) {
            OgretmenDers::create([
                'ogretmen_id' => $ogretmen2->id,
                'ders_id' => $ders->id,
            ]);
        }

        // Dr. Öğr. Üyesi Mehmet Kaya - Programlama Dersleri
        $ogretmen3 = Ogretmen::where('email', 'mehmet.kaya@universite.edu.tr')->first();
        $dersler3 = Ders::whereIn('ders_kodu', ['BM101', 'BM301'])->get();
        foreach ($dersler3 as $ders) {
            OgretmenDers::create([
                'ogretmen_id' => $ogretmen3->id,
                'ders_id' => $ders->id,
            ]);
        }

        // Dr. Öğr. Üyesi Fatma Şahin - Veri Yapıları ve İşletim Sistemleri
        $ogretmen4 = Ogretmen::where('email', 'fatma.sahin@universite.edu.tr')->first();
        $dersler4 = Ders::whereIn('ders_kodu', ['BM201', 'BM302'])->get();
        foreach ($dersler4 as $ders) {
            OgretmenDers::create([
                'ogretmen_id' => $ogretmen4->id,
                'ders_id' => $ders->id,
            ]);
        }

        // Öğr. Gör. Ali Çelik - Web Programlama ve Bilgisayar Ağları
        $ogretmen5 = Ogretmen::where('email', 'ali.celik@universite.edu.tr')->first();
        $dersler5 = Ders::whereIn('ders_kodu', ['BM203', 'BM303'])->get();
        foreach ($dersler5 as $ders) {
            OgretmenDers::create([
                'ogretmen_id' => $ogretmen5->id,
                'ders_id' => $ders->id,
            ]);
        }

        // Öğr. Gör. Zeynep Arslan - Matematik ve Fizik
        $ogretmen6 = Ogretmen::where('email', 'zeynep.arslan@universite.edu.tr')->first();
        $dersler6 = Ders::whereIn('ders_kodu', ['BM102', 'BM103'])->get();
        foreach ($dersler6 as $ders) {
            OgretmenDers::create([
                'ogretmen_id' => $ogretmen6->id,
                'ders_id' => $ders->id,
            ]);
        }

        // Prof. Dr. Hasan Özdemir - Elektrik Devreleri
        $ogretmen7 = Ogretmen::where('email', 'hasan.ozdemir@universite.edu.tr')->first();
        $dersler7 = Ders::whereIn('ders_kodu', ['EE101'])->get();
        foreach ($dersler7 as $ders) {
            OgretmenDers::create([
                'ogretmen_id' => $ogretmen7->id,
                'ders_id' => $ders->id,
            ]);
        }

        // Doç. Dr. Elif Yıldız - Dijital Elektronik
        $ogretmen8 = Ogretmen::where('email', 'elif.yildiz@universite.edu.tr')->first();
        $dersler8 = Ders::whereIn('ders_kodu', ['EE201'])->get();
        foreach ($dersler8 as $ders) {
            OgretmenDers::create([
                'ogretmen_id' => $ogretmen8->id,
                'ders_id' => $ders->id,
            ]);
        }

        // Dr. Öğr. Üyesi Can Aydın - Sinyal İşleme
        $ogretmen9 = Ogretmen::where('email', 'can.aydin@universite.edu.tr')->first();
        $dersler9 = Ders::whereIn('ders_kodu', ['EE301'])->get();
        foreach ($dersler9 as $ders) {
            OgretmenDers::create([
                'ogretmen_id' => $ogretmen9->id,
                'ders_id' => $ders->id,
            ]);
        }

        // Öğr. Gör. Selin Koç - Ek Dersler
        $ogretmen10 = Ogretmen::where('email', 'selin.koc@universite.edu.tr')->first();
        $dersler10 = Ders::whereIn('ders_kodu', ['BM101', 'BM201'])->get();
        foreach ($dersler10 as $ders) {
            OgretmenDers::create([
                'ogretmen_id' => $ogretmen10->id,
                'ders_id' => $ders->id,
            ]);
        }
    }
}
