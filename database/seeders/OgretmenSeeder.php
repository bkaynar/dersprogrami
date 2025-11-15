<?php

namespace Database\Seeders;

use App\Models\Ogretmen;
use Illuminate\Database\Seeder;

class OgretmenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ogretmenler = [
            [
                'isim' => 'Prof. Dr. Ahmet Yılmaz',
                'email' => 'ahmet.yilmaz@universite.edu.tr',
                'unvan' => 'Profesör',
            ],
            [
                'isim' => 'Doç. Dr. Ayşe Demir',
                'email' => 'ayse.demir@universite.edu.tr',
                'unvan' => 'Doçent',
            ],
            [
                'isim' => 'Dr. Öğr. Üyesi Mehmet Kaya',
                'email' => 'mehmet.kaya@universite.edu.tr',
                'unvan' => 'Dr. Öğretim Üyesi',
            ],
            [
                'isim' => 'Dr. Öğr. Üyesi Fatma Şahin',
                'email' => 'fatma.sahin@universite.edu.tr',
                'unvan' => 'Dr. Öğretim Üyesi',
            ],
            [
                'isim' => 'Öğr. Gör. Ali Çelik',
                'email' => 'ali.celik@universite.edu.tr',
                'unvan' => 'Öğretim Görevlisi',
            ],
            [
                'isim' => 'Öğr. Gör. Zeynep Arslan',
                'email' => 'zeynep.arslan@universite.edu.tr',
                'unvan' => 'Öğretim Görevlisi',
            ],
            [
                'isim' => 'Prof. Dr. Hasan Özdemir',
                'email' => 'hasan.ozdemir@universite.edu.tr',
                'unvan' => 'Profesör',
            ],
            [
                'isim' => 'Doç. Dr. Elif Yıldız',
                'email' => 'elif.yildiz@universite.edu.tr',
                'unvan' => 'Doçent',
            ],
            [
                'isim' => 'Dr. Öğr. Üyesi Can Aydın',
                'email' => 'can.aydin@universite.edu.tr',
                'unvan' => 'Dr. Öğretim Üyesi',
            ],
            [
                'isim' => 'Öğr. Gör. Selin Koç',
                'email' => 'selin.koc@universite.edu.tr',
                'unvan' => 'Öğretim Görevlisi',
            ],
        ];

        foreach ($ogretmenler as $ogretmen) {
            Ogretmen::create($ogretmen);
        }
    }
}
