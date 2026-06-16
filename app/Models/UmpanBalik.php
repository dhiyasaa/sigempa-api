<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UmpanBalik extends Model
{
    protected $table = 'umpan_baliks';

    protected $fillable = [
        'nama',
        'email',
        'kategori',
        'pesan',
    ];
}