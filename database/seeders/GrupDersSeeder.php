<?php

namespace Database\Seeders;

use App\Models\Ders;
use App\Models\GrupDers;
use App\Models\OgrenciGrubu;
use Illuminate\Database\Seeder;

class GrupDersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Sınıf Bilgisayar Mühendisliği gruplarına 1. sınıf dersleri
        $birincSinifDersler = Ders::whereIn('ders_kodu', ['BM101', 'BM102', 'BM103'])->get();
        $birincSinifGruplar = OgrenciGrubu::where('isim', 'like', '%1-A%')
            ->orWhere('isim', 'like', '%1-B%')
            ->orWhere('isim', 'like', '%1-C%')
            ->get();

        foreach ($birincSinifGruplar as $grup) {
            foreach ($birincSinifDersler as $ders) {
                GrupDers::create([
                    'ogrenci_grup_id' => $grup->id,
                    'ders_id' => $ders->id,
                ]);
            }
        }

        // 2. Sınıf Bilgisayar Mühendisliği gruplarına 2. sınıf dersleri
        $ikinciSinifDersler = Ders::whereIn('ders_kodu', ['BM201', 'BM202', 'BM203'])->get();
        $ikinciSinifGruplar = OgrenciGrubu::where('isim', 'like', '%2-A%')
            ->orWhere('isim', 'like', '%2-B%')
            ->get();

        foreach ($ikinciSinifGruplar as $grup) {
            foreach ($ikinciSinifDersler as $ders) {
                GrupDers::create([
                    'ogrenci_grup_id' => $grup->id,
                    'ders_id' => $ders->id,
                ]);
            }
        }

        // 3. Sınıf Bilgisayar Mühendisliği gruplarına 3. sınıf dersleri
        $ucuncuSinifDersler = Ders::whereIn('ders_kodu', ['BM301', 'BM302', 'BM303'])->get();
        $ucuncuSinifGruplar = OgrenciGrubu::where('isim', 'like', '%3-A%')->get();

        foreach ($ucuncuSinifGruplar as $grup) {
            foreach ($ucuncuSinifDersler as $ders) {
                GrupDers::create([
                    'ogrenci_grup_id' => $grup->id,
                    'ders_id' => $ders->id,
                ]);
            }
        }

        // 4. Sınıf Bilgisayar Mühendisliği gruplarına 4. sınıf dersleri
        $dorduncuSinifDersler = Ders::whereIn('ders_kodu', ['BM401', 'BM402', 'BM403'])->get();
        $dorduncuSinifGruplar = OgrenciGrubu::where('isim', 'like', '%4-A%')->get();

        foreach ($dorduncuSinifGruplar as $grup) {
            foreach ($dorduncuSinifDersler as $ders) {
                GrupDers::create([
                    'ogrenci_grup_id' => $grup->id,
                    'ders_id' => $ders->id,
                ]);
            }
        }

        // Elektrik Elektronik Mühendisliği 1. sınıf
        $elektrikDersler = Ders::whereIn('ders_kodu', ['EE101', 'BM102', 'BM103'])->get();
        $elektrikGrubu = OgrenciGrubu::where('isim', 'like', 'Elektrik%1-A')->first();

        if ($elektrikGrubu) {
            foreach ($elektrikDersler as $ders) {
                GrupDers::create([
                    'ogrenci_grup_id' => $elektrikGrubu->id,
                    'ders_id' => $ders->id,
                ]);
            }
        }

        // Elektrik Elektronik Mühendisliği 2. sınıf
        $elektrikDersler2 = Ders::whereIn('ders_kodu', ['EE201', 'BM201'])->get();
        $elektrikGrubu2 = OgrenciGrubu::where('isim', 'like', 'Elektrik%2-A')->first();

        if ($elektrikGrubu2) {
            foreach ($elektrikDersler2 as $ders) {
                GrupDers::create([
                    'ogrenci_grup_id' => $elektrikGrubu2->id,
                    'ders_id' => $ders->id,
                ]);
            }
        }

        // Elektrik Elektronik Mühendisliği 3. sınıf
        $elektrikDersler3 = Ders::whereIn('ders_kodu', ['EE301', 'BM302'])->get();
        $elektrikGrubu3 = OgrenciGrubu::where('isim', 'like', 'Elektrik%3-A')->first();

        if ($elektrikGrubu3) {
            foreach ($elektrikDersler3 as $ders) {
                GrupDers::create([
                    'ogrenci_grup_id' => $elektrikGrubu3->id,
                    'ders_id' => $ders->id,
                ]);
            }
        }
    }
}
