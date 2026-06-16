<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('beritas', function (Blueprint $table) {
            if (!Schema::hasColumn('beritas', 'gambar')) {
                $table->text('gambar')->nullable()->after('ringkasan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('beritas', function (Blueprint $table) {
            if (Schema::hasColumn('beritas', 'gambar')) {
                $table->dropColumn('gambar');
            }
        });
    }
};
