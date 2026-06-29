<?php

namespace App\Services;

use App\Models\FloodReport;
use App\Models\WaterGate;
use App\Events\TmaThresholdExceeded;
use App\Events\FloodReportVerified;

class AdminService
{
    /**
     * Verify citizen flood report.
     */
    public function verifyReport(int $id): FloodReport
    {
        $report = FloodReport::findOrFail($id);
        $report->verification_status = "verified";
        $report->save();

        event(new FloodReportVerified($report));

        return $report;
    }

    /**
     * Reject citizen flood report.
     */
    public function rejectReport(int $id): FloodReport
    {
        $report = FloodReport::findOrFail($id);
        $report->verification_status = "rejected";
        $report->save();

        return $report;
    }

    /**
     * Update TMA Water Gate level and calculate danger status.
     */
    public function updateTma(int $id, float $waterLevelCm): WaterGate
    {
        $gate = WaterGate::findOrFail($id);

        $oldStatus = WaterGate::calculateDangerStatus(
            (float) $gate->water_level_cm,
        );
        $newStatus = WaterGate::calculateDangerStatus($waterLevelCm);

        $gate->water_level_cm = $waterLevelCm;
        $gate->danger_status = $newStatus;
        $gate->last_updated = now();
        $gate->save();

        // Check if severity increased to Siaga_2 or Siaga_1
        $severity = [
            "Normal" => 0,
            "Siaga_3" => 1,
            "Siaga_2" => 2,
            "Siaga_1" => 3,
        ];

        $oldSeverity = $severity[$oldStatus] ?? 0;
        $newSeverity = $severity[$newStatus] ?? 0;

        if ($newSeverity > $oldSeverity && $newSeverity >= 2) {
            event(new TmaThresholdExceeded($gate, $oldStatus, $newStatus));
        }

        return $gate;
    }

    /**
     * Get list of users with pending verification status
     */
    public function getPendingUsers()
    {
        return \App\Models\User::where("status", "pending")
            ->where("role", "Pengelola_Posko")
            ->orderBy("created_at", "asc")
            ->get();
    }

    /**
     * Approve user account
     */
    public function approveUser(int $id)
    {
        $user = \App\Models\User::findOrFail($id);
        $user->status = "approved";
        $user->save();

        return $user;
    }

    /**
     * Reject user account
     */
    public function rejectUser(int $id)
    {
        $user = \App\Models\User::findOrFail($id);
        $user->status = "rejected";
        $user->save();

        return $user;
    }
}
