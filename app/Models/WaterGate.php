<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaterGate extends Model
{
    protected $primaryKey = 'gate_id';

    protected $fillable = [
        'gate_name',
        'river_name',
        'water_level_cm',
        'danger_status',
        'last_updated',
    ];

    protected $casts = [
        'last_updated' => 'datetime',
    ];
}
