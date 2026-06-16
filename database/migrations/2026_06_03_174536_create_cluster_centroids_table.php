<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cluster_centroids', function (Blueprint $table) {
            $table->id();
            $table->string('cluster');
            $table->string('label');
            $table->string('status');
            $table->double('magnitudo');
            $table->double('kedalaman');
            $table->string('color');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cluster_centroids');
    }
};