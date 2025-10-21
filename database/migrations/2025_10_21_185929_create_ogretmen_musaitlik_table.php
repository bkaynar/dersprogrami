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
        Schema::create('ogretmen_musaitlik', function (Blueprint $table) {
         $table->id();
        $table->foreignId('ogretmen_id')->constrained('ogretmenler')->onDelete('cascade');
        $table->foreignId('zaman_dilimi_id')->constrained('zaman_dilimleri')->onDelete('cascade');
        $table->enum('musaitlik_tipi', ['musait', 'musait_degil', 'tercih_edilen'])->default('musait');

        // Bir hoca bir zaman dilimi için tek bir kurala sahip olabilir
        $table->unique(['ogretmen_id', 'zaman_dilimi_id'], 'idx_ogretmen_zaman_benzersiz');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ogretmen_musaitlik');
    }
};
