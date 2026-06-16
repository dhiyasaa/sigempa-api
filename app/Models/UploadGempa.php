<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UploadGempa extends Model
{
    protected $table = 'upload_gempas';

    protected $fillable = [
        'tanggal',
        'jam',
        'lintang',
        'bujur',
        'magnitudo',
        'kedalaman',
        'wilayah',
        'potensi',
        'status',
        'color'
    ];
}