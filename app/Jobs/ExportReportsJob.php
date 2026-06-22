<?php

namespace App\Jobs;

use App\Models\FloodReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ExportReportsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $filePath;

    /**
     * Create a new job instance.
     */
    public function __construct(string $filePath = 'reports/flood_reports.csv')
    {
        $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $reports = FloodReport::with('user')->orderBy('created_at', 'desc')->get();

        // Create CSV Content
        $headers = [
            'Report ID',
            'Reporter Name',
            'Street Name',
            'Kecamatan',
            'Kelurahan',
            'Water Height (cm)',
            'Latitude',
            'Longitude',
            'Road Access Status',
            'Power Outage',
            'Water Rising',
            'Needs Evacuation',
            'Citizens Isolated',
            'Additional Info',
            'Verification Status',
            'Created At'
        ];

        $callback = function() use ($reports, $headers) {
            $file = fopen('php://temp', 'r+');
            fputcsv($file, $headers);

            foreach ($reports as $report) {
                fputcsv($file, [
                    $report->report_id,
                    $report->user ? $report->user->fullname : 'N/A',
                    $report->street_name,
                    $report->kecamatan,
                    $report->kelurahan,
                    $report->water_height_cm,
                    $report->latitude,
                    $report->longitude,
                    $report->status_akses_jalan,
                    $report->listrik_padam ? 'Yes' : 'No',
                    $report->air_masih_naik ? 'Yes' : 'No',
                    $report->butuh_evakuasi ? 'Yes' : 'No',
                    $report->warga_terisolasi ? 'Yes' : 'No',
                    $report->keterangan_bebas,
                    $report->verification_status,
                    $report->created_at ? $report->created_at->toDateTimeString() : ''
                ]);
            }

            rewind($file);
            $csv = stream_get_contents($file);
            fclose($file);

            return $csv;
        };

        $csvData = $callback();

        // Save to storage
        Storage::disk('local')->put($this->filePath, $csvData);
    }
}
