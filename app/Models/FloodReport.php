<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FloodReport extends Model
{
    protected $primaryKey = 'report_id';

    protected $fillable = [
        'user_id',
        'water_height_cm',
        'street_name',
        'latitude',
        'longitude',
        'photo_evidence',
        'verification_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
