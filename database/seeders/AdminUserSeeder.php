<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin kullanıcısı oluştur (iki faktörlü doğrulama olmadan)
        User::create([
            'name' => 'Admin',
            'kullaniciAdi' => 'admin',
            'password' => Hash::make('password'), // Varsayılan şifre: password
            // two_factor alanları null kalacak (iki faktörlü doğrulama yok)
        ]);

        $this->command->info('Admin kullanıcısı oluşturuldu!');
        $this->command->info('Kullanıcı Adı: admin');
        $this->command->info('Şifre: password');
    }
}
