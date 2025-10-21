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
        Schema::create('zaman_dilimleri', function (Blueprint $table) {
          $table->id();
        $table->enum('haftanin_gunu', ['pazartesi', 'sali', 'carsamba', 'persembe', 'cuma', 'cumartesi', 'pazar']);
        $table->time('baslangic_saati');
        $table->time('bitis_saati');
        // Bu tabloda timestamps'e gerek yok, çünkü verisi sabit olacak
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zaman_dilimleri');
    }
};
