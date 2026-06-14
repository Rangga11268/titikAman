<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShelterNeed extends Model
{
    protected $fillable = [
        'shelter_id',
        'item_name',
        'quantity_need',
        'quantity_fulfilled',
        'urgency',
    ];

    public function shelter()
    {
        return $this->belongsTo(Shelter::class, 'shelter_id');
    }

    public function donations()
    {
        return $this->hasMany(Donation::class, 'need_id');
    }
}
