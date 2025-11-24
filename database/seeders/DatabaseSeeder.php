<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Önce admin kullanıcısını oluştur
        $this->call([
            AdminUserSeeder::class,
        ]);

        // Sonra diğer bağımsız tabloları oluştur
        /*$this->call([
            UserSeeder::class,
            MekanSeeder::class,
            OgrenciGrubuSeeder::class,
            ZamanDilimSeeder::class,
            DersSeeder::class,
            OgretmenSeeder::class,
        ]);
        */

        // Sonra ilişkisel tabloları oluştur (foreign key bağımlılıkları var)
        /*
        $this->call([
            DersMekanGeresinimSeeder::class,
            GrupDersSeeder::class,
            OgretmenDersSeeder::class,
            OgretmenMusaitlikSeeder::class,
            GrupKisitlamaSeeder::class,
        ]);
        */
    }
}
