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
        Schema::create('mekanlar', function (Blueprint $table) {
           $table->id();
        $table->string('isim', 100);
        $table->integer('kapasite');
        $table->enum('mekan_tipi', ['derslik', 'laboratuvar', 'konferans_salonu']);
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mekanlar');
    }
};
