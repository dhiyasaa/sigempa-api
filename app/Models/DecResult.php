<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DecResult extends Model
{
  protected $fillable = [
    'magnitudo',
    'kedalaman',
    'jarak_c1',
    'jarak_c2',
    'jarak_c3',
    'cluster'
]; 
}
