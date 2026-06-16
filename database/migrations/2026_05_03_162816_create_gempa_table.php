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
        Schema::create('gempas', function (Blueprint $table) {
            $table->id();
            $table->string('tanggal');
            $table->string('jam');
            $table->string('lintang')->nullable();
            $table->string('bujur')->nullable();
            $table->double('magnitudo')->nullable();
            $table->string('kedalaman')->nullable();
            $table->text('wilayah')->nullable();
            $table->string('potensi')->nullable();

            // 🔥 WAJIB BUAT SISTEM KAMU
            $table->string('status')->nullable();
            $table->string('color')->nullable();
            $table->string('source')->default('BMKG');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gempas'); // ✅ FIX
    }
};