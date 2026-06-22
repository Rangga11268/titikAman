<?php

namespace App\Services;

use App\Models\FloodReport;
use App\Models\WaterGate;
use App\Events\TmaThresholdExceeded;

class AdminService
{
    /**
     * Verify citizen flood report.
     */
    public function verifyReport(int $id): FloodReport
    {
        $report = FloodReport::findOrFail($id);
        $report->verification_status = 'verified';
        $report->save();

        return $report;
    }

    /**
     * Reject citizen flood report.
     */
    public function rejectReport(int $id): FloodReport
    {
        $report = FloodReport::findOrFail($id);
        $report->verification_status = 'rejected';
        $report->save();

        return $report;
    }

    /**
     * Update TMA Water Gate level and calculate danger status.
     */
    public function updateTma(int $id, float $waterLevelCm): WaterGate
    {
        $gate = WaterGate::findOrFail($id);
        
        $oldStatus = $gate->danger_status;

        // Auto calculate danger status based on threshold:
        // > 250 cm → 'Siaga_1'
        // 150 - 250 cm → 'Siaga_2'
        // 80 - 150 cm → 'Siaga_3'
        // < 80 cm → 'Normal'
        if ($waterLevelCm > 250) {
            $newStatus = 'Siaga_1';
        } elseif ($waterLevelCm >= 150) {
            $newStatus = 'Siaga_2';
        } elseif ($waterLevelCm >= 80) {
            $newStatus = 'Siaga_3';
        } else {
            $newStatus = 'Normal';
        }

        $gate->water_level_cm = $waterLevelCm;
        $gate->danger_status = $newStatus;
        $gate->last_updated = now();
        $gate->save();

        // Check if severity increased to Siaga_2 or Siaga_1
        $severity = [
            'Normal' => 0,
            'Siaga_3' => 1,
            'Siaga_2' => 2,
            'Siaga_1' => 3
        ];

        $oldSeverity = $severity[$oldStatus] ?? 0;
        $newSeverity = $severity[$newStatus] ?? 0;

        if ($newSeverity > $oldSeverity && $newSeverity >= 2) {
            event(new TmaThresholdExceeded($gate, $oldStatus, $newStatus));
        }

        return $gate;
    }
}
