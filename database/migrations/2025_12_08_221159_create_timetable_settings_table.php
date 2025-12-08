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
        Schema::create('timetable_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('min_block_size')->default(2)->comment('Minimum ders blok süresi (saat)');
            $table->integer('max_block_size')->default(3)->comment('Maximum ders blok süresi (saat)');
            $table->boolean('enforce_consecutive')->default(true)->comment('Blokların arka arkaya olması zorunlu mu?');
            $table->json('split_rules')->comment('Haftalık saat dağılım kuralları. Örn: {"4":[2,2], "5":[3,2], "6":[3,3]}');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timetable_settings');
    }
};
