<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $fillable = [
        'donor_id',
        'need_id',
        'quantity_donated',
        'shipping_receipt_no',
        'proof_photo',
        'status',
        'donated_at',
    ];

    protected $casts = [
        'donated_at' => 'datetime',
    ];

    public function donor()
    {
        return $this->belongsTo(User::class, 'donor_id');
    }

    public function shelterNeed()
    {
        return $this->belongsTo(ShelterNeed::class, 'need_id');
    }
}
