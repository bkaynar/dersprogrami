<?php

namespace Database\Seeders;

use App\Models\Ders;
use Illuminate\Database\Seeder;

class DersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dersler = [
            [
                'ders_kodu' => 'BM101',
                'isim' => 'Bilgisayar Programlama I',
                'haftalik_saat' => 4,
            ],
            [
                'ders_kodu' => 'BM102',
                'isim' => 'Matematik I',
                'haftalik_saat' => 4,
            ],
            [
                'ders_kodu' => 'BM103',
                'isim' => 'Fizik I',
                'haftalik_saat' => 3,
            ],
            [
                'ders_kodu' => 'BM201',
                'isim' => 'Veri Yapıları',
                'haftalik_saat' => 4,
            ],
            [
                'ders_kodu' => 'BM202',
                'isim' => 'Veritabanı Sistemleri',
                'haftalik_saat' => 3,
            ],
            [
                'ders_kodu' => 'BM203',
                'isim' => 'Web Programlama',
                'haftalik_saat' => 3,
            ],
            [
                'ders_kodu' => 'BM301',
                'isim' => 'Nesne Yönelimli Programlama',
                'haftalik_saat' => 4,
            ],
            [
                'ders_kodu' => 'BM302',
                'isim' => 'İşletim Sistemleri',
                'haftalik_saat' => 3,
            ],
            [
                'ders_kodu' => 'BM303',
                'isim' => 'Bilgisayar Ağları',
                'haftalik_saat' => 3,
            ],
            [
                'ders_kodu' => 'BM401',
                'isim' => 'Yapay Zeka',
                'haftalik_saat' => 4,
            ],
            [
                'ders_kodu' => 'BM402',
                'isim' => 'Makine Öğrenmesi',
                'haftalik_saat' => 4,
            ],
            [
                'ders_kodu' => 'BM403',
                'isim' => 'Yazılım Mühendisliği',
                'haftalik_saat' => 3,
            ],
            [
                'ders_kodu' => 'EE101',
                'isim' => 'Elektrik Devreleri I',
                'haftalik_saat' => 4,
            ],
            [
                'ders_kodu' => 'EE201',
                'isim' => 'Dijital Elektronik',
                'haftalik_saat' => 4,
            ],
            [
                'ders_kodu' => 'EE301',
                'isim' => 'Sinyal İşleme',
                'haftalik_saat' => 3,
            ],
        ];

        foreach ($dersler as $ders) {
            Ders::create($ders);
        }
    }
}
