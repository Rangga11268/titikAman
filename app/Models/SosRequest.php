<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SosRequest extends Model
{
    protected $fillable = [
        'sender_id',
        'latitude',
        'longitude',
        'people_trapped',
        'priority_level',
        'elderly_count',
        'infant_count',
        'pregnant_count',
        'description',
        'status',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function rescueMissions()
    {
        return $this->hasMany(RescueMission::class, 'sos_id');
    }
}
