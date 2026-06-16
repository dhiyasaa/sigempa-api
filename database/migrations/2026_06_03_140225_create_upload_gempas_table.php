<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('upload_gempas', function (Blueprint $table) {
            $table->id();
            $table->string('tanggal')->nullable();
            $table->string('jam')->nullable();
            $table->string('lintang')->nullable();
            $table->string('bujur')->nullable();
            $table->double('magnitudo')->nullable();
            $table->string('kedalaman')->nullable();
            $table->text('wilayah')->nullable();
            $table->string('potensi')->nullable();
            $table->string('status')->nullable();
            $table->string('color')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('upload_gempas');
    }
};