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
        Schema::create('ogretmenler', function (Blueprint $table) {
            $table->id();
            $table->string('isim');
            $table->string('unvan', 100)->nullable();
            $table->string('email')->unique()->nullable();
            $table->timestamps(); // created_at ve updated_at ekler
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ogretmenler');
    }
};
