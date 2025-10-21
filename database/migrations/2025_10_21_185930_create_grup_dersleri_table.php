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
        Schema::create('grup_dersleri', function (Blueprint $table) {
            $table->foreignId('ogrenci_grup_id')->constrained('ogrenci_gruplari')->onDelete('cascade');
            $table->foreignId('ders_id')->constrained('dersler')->onDelete('cascade');

            $table->primary(['ogrenci_grup_id', 'ders_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grup_dersleri');
    }
};
