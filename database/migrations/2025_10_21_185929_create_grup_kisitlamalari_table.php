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
        Schema::create('grup_kisitlamalari', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ogrenci_grup_id')->constrained('ogrenci_gruplari')->onDelete('cascade');
            $table->foreignId('zaman_dilimi_id')->constrained('zaman_dilimleri')->onDelete('cascade');
            $table->boolean('musait_mi')->default(true);

            $table->unique(['ogrenci_grup_id', 'zaman_dilimi_id'], 'idx_grup_zaman_benzersiz');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grup_kisitlamalari');
    }
};
