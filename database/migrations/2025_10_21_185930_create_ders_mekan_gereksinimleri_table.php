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
        Schema::create('ders_mekan_gereksinimleri', function (Blueprint $table) {
           $table->id();
        $table->foreignId('ders_id')->constrained('dersler')->onDelete('cascade');
        $table->enum('mekan_tipi', ['derslik', 'laboratuvar', 'konferans_salonu']);
        $table->enum('gereksinim_tipi', ['zorunlu', 'olabilir']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ders_mekan_gereksinimleri');
    }
};
