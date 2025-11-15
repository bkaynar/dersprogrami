<?php

namespace Database\Seeders;

use App\Models\OgrenciGrubu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OgrenciGrubuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ana Gruplar (Bölümler)
        $bilgisayarMuh = OgrenciGrubu::create([
            'isim' => 'Bilgisayar Mühendisliği',
            'yil' => 1,
            'ogrenci_sayisi' => 120,
            'ust_grup_id' => null,
        ]);

        $elektrikMuh = OgrenciGrubu::create([
            'isim' => 'Elektrik-Elektronik Mühendisliği',
            'yil' => 1,
            'ogrenci_sayisi' => 100,
            'ust_grup_id' => null,
        ]);

        $makine = OgrenciGrubu::create([
            'isim' => 'Makine Mühendisliği',
            'yil' => 1,
            'ogrenci_sayisi' => 90,
            'ust_grup_id' => null,
        ]);

        // Bilgisayar Mühendisliği Şubeleri
        OgrenciGrubu::create([
            'isim' => 'Bilgisayar Mühendisliği 1-A',
            'yil' => 1,
            'ogrenci_sayisi' => 40,
            'ust_grup_id' => $bilgisayarMuh->id,
        ]);

        OgrenciGrubu::create([
            'isim' => 'Bilgisayar Mühendisliği 1-B',
            'yil' => 1,
            'ogrenci_sayisi' => 40,
            'ust_grup_id' => $bilgisayarMuh->id,
        ]);

        OgrenciGrubu::create([
            'isim' => 'Bilgisayar Mühendisliği 1-C',
            'yil' => 1,
            'ogrenci_sayisi' => 40,
            'ust_grup_id' => $bilgisayarMuh->id,
        ]);

        // 2. Sınıf Grupları
        OgrenciGrubu::create([
            'isim' => 'Bilgisayar Mühendisliği 2. Sınıf',
            'yil' => 2,
            'ogrenci_sayisi' => 110,
            'ust_grup_id' => null,
        ]);

        OgrenciGrubu::create([
            'isim' => 'Elektrik-Elektronik Mühendisliği 2. Sınıf',
            'yil' => 2,
            'ogrenci_sayisi' => 95,
            'ust_grup_id' => null,
        ]);

        // 3. Sınıf Grupları
        OgrenciGrubu::create([
            'isim' => 'Bilgisayar Mühendisliği 3. Sınıf',
            'yil' => 3,
            'ogrenci_sayisi' => 105,
            'ust_grup_id' => null,
        ]);

        OgrenciGrubu::create([
            'isim' => 'Makine Mühendisliği 3. Sınıf',
            'yil' => 3,
            'ogrenci_sayisi' => 85,
            'ust_grup_id' => null,
        ]);

        // 4. Sınıf Grupları
        OgrenciGrubu::create([
            'isim' => 'Bilgisayar Mühendisliği 4. Sınıf',
            'yil' => 4,
            'ogrenci_sayisi' => 100,
            'ust_grup_id' => null,
        ]);

        OgrenciGrubu::create([
            'isim' => 'Elektrik-Elektronik Mühendisliği 4. Sınıf',
            'yil' => 4,
            'ogrenci_sayisi' => 88,
            'ust_grup_id' => null,
        ]);
    }
}
