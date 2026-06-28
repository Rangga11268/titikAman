<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shelter extends Model
{
    protected $primaryKey = 'shelter_id';

    protected $fillable = [
        'shelter_name',
        'address',
        'max_capacity',
        'current_occupants',
        'has_toilet_facilities',
        'status',
        'latitude',
        'longitude',
        'facilities',
        'photo',
    ];

    protected $casts = [
        'facilities' => 'array',
    ];

    public function shelterNeeds()
    {
        return $this->hasMany(ShelterNeed::class, 'shelter_id');
    }
}
