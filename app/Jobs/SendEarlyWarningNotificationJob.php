<?php

namespace App\Jobs;

use App\Models\WaterGate;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendEarlyWarningNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $waterGate;
    public $newStatus;

    /**
     * Create a new job instance.
     */
    public function __construct(WaterGate $waterGate, string $newStatus)
    {
        $this->waterGate = $waterGate;
        $this->newStatus = $newStatus;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $riverName = $this->waterGate->river_name;
        
        // Define target areas based on river flow
        $targetKecamatan = ['Bekasi Timur', 'Bekasi Selatan', 'Rawalumbu']; // Default matching areas for river flows in Bekasi

        // Query citizens in the target area
        $citizens = User::where('role', 'Warga')
            ->whereIn('kecamatan', $targetKecamatan)
            ->get();

        foreach ($citizens as $citizen) {
            $message = sprintf(
                "PERINGATAN DINI: Pintu Air %s (%s) mengalami kenaikan Tinggi Muka Air menjadi %.2f cm dengan status %s. Harap waspada untuk warga di wilayah %s, %s.",
                $this->waterGate->gate_name,
                $this->waterGate->river_name,
                $this->waterGate->water_level_cm,
                $this->newStatus,
                $citizen->kelurahan,
                $citizen->kecamatan
            );

            // Log the simulated early warning notification
            Log::info("SIMULATED SMS/NOTIFICATION SENT TO {$citizen->phone} ({$citizen->fullname}): {$message}");
        }
    }
}
