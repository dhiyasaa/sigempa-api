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
        Schema::create('dec_results', function (Blueprint $table) {
            $table->id();
            $table->double('magnitudo');
            $table->double('kedalaman');

            $table->double('jarak_c1');
            $table->double('jarak_c2');
            $table->double('jarak_c3');

            $table->string('cluster'); // Aman / Waspada / Siaga
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dec_results');
    }
};