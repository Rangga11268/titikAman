<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shelter extends Model
{
    protected $fillable = [
        'shelter_name',
        'address',
        'max_capacity',
        'current_occupants',
        'status',
        'latitude',
        'longitude',
    ];

    public function shelterNeeds()
    {
        return $this->hasMany(ShelterNeed::class, 'shelter_id');
    }
}
