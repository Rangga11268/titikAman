<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RescueMission extends Model
{
    protected $fillable = [
        'sos_id',
        'volunteer_id',
        'assigned_at',
        'resolved_at',
     ];

     protected $casts = [
         'assigned_at' => 'datetime',
         'resolved_at' => 'datetime',
     ];

     public function sosRequest()
     {
         return $this->belongsTo(SosRequest::class, 'sos_id');
     }

     public function volunteer()
     {
         return $this->belongsTo(User::class, 'volunteer_id');
     }
}
