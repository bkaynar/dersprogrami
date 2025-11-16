<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // MySQL ENUM değiştirme - kolonu drop edip yeniden oluştur
        DB::statement("ALTER TABLE mekanlar MODIFY COLUMN mekan_tipi ENUM('Derslik', 'Amfi', 'Laboratuvar', 'Salon', 'Atölye') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Geri al - eski ENUM değerlerine dön
        DB::statement("ALTER TABLE mekanlar MODIFY COLUMN mekan_tipi ENUM('derslik', 'laboratuvar', 'konferans_salonu') NOT NULL");
    }
};
