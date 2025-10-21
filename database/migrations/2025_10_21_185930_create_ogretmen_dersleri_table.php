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
        Schema::create('ogretmen_dersleri', function (Blueprint $table) {
            $table->foreignId('ogretmen_id')->constrained('ogretmenler')->onDelete('cascade');
            $table->foreignId('ders_id')->constrained('dersler')->onDelete('cascade');

            // İkisi birlikte birincil anahtar (Primary Key)
            $table->primary(['ogretmen_id', 'ders_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ogretmen_dersleri');
    }
};
