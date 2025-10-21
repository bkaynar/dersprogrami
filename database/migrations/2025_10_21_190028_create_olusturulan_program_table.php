<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('olusturulan_program', function (Blueprint $table) {
            $table->id();
            $table->string('akademik_donem', 100);
            $table->foreignId('ders_id')->constrained('dersler')->onDelete('cascade');
            $table->foreignId('ogretmen_id')->constrained('ogretmenler')->onDelete('cascade');
            $table->foreignId('ogrenci_grup_id')->constrained('ogrenci_gruplari')->onDelete('cascade');
            $table->foreignId('mekan_id')->constrained('mekanlar')->onDelete('cascade');
            $table->foreignId('zaman_dilimi_id')->constrained('zaman_dilimleri')->onDelete('cascade');
            $table->timestamps();

            // Çakışmaları önleyecek benzersizlik (unique) kuralları
            $table->unique(['akademik_donem', 'zaman_dilimi_id', 'ogretmen_id'], 'idx_program_ogretmen_benzersiz');
            $table->unique(['akademik_donem', 'zaman_dilimi_id', 'ogrenci_grup_id'], 'idx_program_grup_benzersiz');
            $table->unique(['akademik_donem', 'zaman_dilimi_id', 'mekan_id'], 'idx_program_mekan_benzersiz');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('olusturulan_program');
    }
};
