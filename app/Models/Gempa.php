<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gempa extends Model
{
    protected $table = 'gempas';

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
        'color',
        'source'
    ];
}