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
        Schema::create('ogrenci_gruplari', function (Blueprint $table) {
           $table->id();
        $table->string('isim', 100);
        $table->integer('yil')->nullable();
        $table->integer('ogrenci_sayisi')->nullable();

        // Kendine referans veren foreign key (Şube/Ana Sınıf ilişkisi)
        $table->unsignedBigInteger('ust_grup_id')->nullable();
        $table->foreign('ust_grup_id')
              ->references('id')
              ->on('ogrenci_gruplari')
              ->onDelete('set null'); // Ana grup silinirse şubenin bağlantısı kopar

        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ogrenci_gruplari');
    }
};
