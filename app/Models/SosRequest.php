<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SosRequest extends Model
{
    protected $primaryKey = 'sos_id';

    protected $fillable = [
        'user_id',
        'latitude',
        'longitude',
        'people_trapped',
        'vulnerable_groups_count',
        'priority_level',
        'description',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function rescueMission()
    {
        return $this->hasOne(RescueMission::class, 'sos_id', 'sos_id');
    }
}
