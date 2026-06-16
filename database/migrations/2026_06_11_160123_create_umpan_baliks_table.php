<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('umpan_baliks', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->nullable();
            $table->string('email')->nullable();
            $table->string('kategori')->nullable();
            $table->text('pesan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('umpan_baliks');
    }
};