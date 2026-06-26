<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaterGate extends Model
{
    protected $primaryKey = 'gate_id';

    protected $fillable = [
        'gate_name',
        'river_name',
        'water_level_cm',
        'danger_status',
        'last_updated',
    ];

    protected $casts = [
        'last_updated' => 'datetime',
        'water_level_cm' => 'float',
    ];

    protected static function booted(): void
    {
        static::saving(function (WaterGate $gate) {
            if ($gate->isDirty('water_level_cm')) {
                $gate->danger_status = self::calculateDangerStatus(
                    (float) $gate->water_level_cm,
                );
            }
        });
    }

    /**
     * Hitung status siaga dari tinggi muka air (cm).
     * > 250 → Siaga_1 | 150–250 → Siaga_2 | 80–150 → Siaga_3 | < 80 → Normal
     */
    public static function calculateDangerStatus(float $waterLevelCm): string
    {
        if ($waterLevelCm > 250) {
            return 'Siaga_1';
        }

        if ($waterLevelCm >= 150) {
            return 'Siaga_2';
        }

        if ($waterLevelCm >= 80) {
            return 'Siaga_3';
        }

        return 'Normal';
    }

    /**
     * Sinkronkan kolom danger_status yang sudah ada agar sesuai water_level_cm.
     */
    public static function syncAllDangerStatuses(): void
    {
        self::all()->each(function (WaterGate $gate) {
            $correct = self::calculateDangerStatus((float) $gate->water_level_cm);

            if ($gate->danger_status !== $correct) {
                $gate->danger_status = $correct;
                $gate->saveQuietly();
            }
        });
    }
}
