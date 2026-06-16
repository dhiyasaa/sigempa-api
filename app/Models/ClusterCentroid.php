<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClusterCentroid extends Model
{
    protected $table = 'cluster_centroids';

    protected $fillable = [
        'cluster',
        'label',
        'status',
        'magnitudo',
        'kedalaman',
        'color'
    ];
}