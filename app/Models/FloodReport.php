<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FloodReport extends Model
{
    protected $primaryKey = 'report_id';

    protected $fillable = [
        'user_id',
        'kecamatan',
        'kelurahan',
        'water_height_cm',
        'street_name',
        'latitude',
        'longitude',
        'status_akses_jalan',
        'listrik_padam',
        'air_masih_naik',
        'butuh_evakuasi',
        'warga_terisolasi',
        'keterangan_bebas',
        'photo_evidence',
        'verification_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
