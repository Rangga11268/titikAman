<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FloodReport extends Model
{
    protected $fillable = [
        'reporter_id',
        'gate_id',
        'water_height_cm',
        'street_name',
        'latitude',
        'longitude',
        'photo_evidence',
        'status',
    ];

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function waterGate()
    {
        return $this->belongsTo(WaterGate::class, 'gate_id');
    }
}
