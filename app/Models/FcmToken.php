<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FcmToken extends Model
{
    protected $table = 'fcm_tokens';

    protected $fillable = [
        'token',
        'latitude',
        'longitude',
        'radius_km',
        'last_seen_at',
    ];
}