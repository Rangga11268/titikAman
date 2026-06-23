<?php

namespace App\Http\Controllers;

use App\Models\Shelter;
use App\Models\WaterGate;
use App\Models\SosRequest;
use App\Models\FloodReport;
use App\Models\Donation;
use Illuminate\Http\Request;

class SharedController extends Controller
{
    /**
     * Dashboard Utama — accessible by all roles.
     * Figma node: 163:1693
     */
    public function dashboard()
    {
        $role = auth()->user()->role;
        if ($role === 'Warga') {
            return redirect()->route('warga.dashboard');
        }

        // Stat Cards
        $titikBanjir      = FloodReport::where('verification_status', 'verified')->count();
        $wargaTerdampak   = \App\Models\User::where('role', 'Warga')->count();
        $sosMenunggu      = SosRequest::where('status', 'waiting')->count();
        $poskoAktif       = Shelter::whereIn('status', ['active', 'full'])->count();

        // Shelters for map markers
        $shelters         = Shelter::whereIn('status', ['active', 'full'])->get();

        // Water gates status
        $waterGates       = WaterGate::orderBy('danger_status', 'desc')->get();

        // Latest SOS requests for queue display
        $latestSos        = SosRequest::with('user')
            ->where('status', 'waiting')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        // Recent flood reports, SOS requests, water gates, and donations for activity log
        $reportsList = FloodReport::with('user')->orderBy('created_at', 'desc')->take(10)->get();
        $sosRequestsList = SosRequest::with('user')->orderBy('created_at', 'desc')->take(10)->get();
        $waterGatesList = WaterGate::orderBy('last_updated', 'desc')->take(5)->get();
        $donationsList = Donation::with(['donor', 'shelterNeed'])->orderBy('created_at', 'desc')->take(10)->get();

        $logs = collect();

        foreach ($reportsList as $r) {
            $logs->push([
                'time' => $r->created_at,
                'type' => 'laporan',
                'badge' => 'LAPORAN',
                'badge_class' => 'badge-gray',
                'detail' => "Laporan Genangan: " . $r->street_name . " (Tinggi Air: " . $r->water_height_cm . " cm)",
                'pic' => $r->user?->fullname ?? 'Anonim',
                'status' => $r->verification_status == 'verified' ? 'Terverifikasi' : ($r->verification_status == 'rejected' ? 'Ditolak' : 'Menunggu'),
                'status_class' => $r->verification_status == 'verified' ? 'badge-green' : ($r->verification_status == 'rejected' ? 'badge-red' : 'badge-yellow'),
            ]);
        }

        foreach ($sosRequestsList as $s) {
            $logs->push([
                'time' => $s->created_at,
                'type' => 'sos',
                'badge' => 'SOS EMERGENCY',
                'badge_class' => 'badge-red',
                'detail' => "Evakuasi " . $s->people_trapped . " warga di " . ($s->user?->kelurahan ?? 'Bekasi') . " (" . ($s->description ?? 'Butuh evakuasi') . ")",
                'pic' => $s->user?->fullname ?? 'Warga',
                'status' => $s->status == 'completed' || $s->status == 'rescued' ? 'Selesai' : ($s->status == 'assigned' ? 'Evakuasi' : 'Mencari Relawan'),
                'status_class' => $s->status == 'completed' || $s->status == 'rescued' ? 'badge-green' : ($s->status == 'assigned' ? 'badge-blue' : 'badge-yellow'),
            ]);
        }

        foreach ($waterGatesList as $w) {
            $logs->push([
                'time' => $w->last_updated ?? now(),
                'type' => 'pintu_air',
                'badge' => 'PINTU AIR',
                'badge_class' => 'badge-blue',
                'detail' => "Tinggi Muka Air " . $w->gate_name . " berstatus " . str_replace('_', ' ', $w->danger_status) . " (" . $w->water_level_cm . " cm)",
                'pic' => 'Petugas Lapangan',
                'status' => str_replace('_', ' ', $w->danger_status),
                'status_class' => $w->danger_status == 'Siaga_1' ? 'badge-red' : ($w->danger_status == 'Siaga_2' ? 'badge-orange' : ($w->danger_status == 'Siaga_3' ? 'badge-yellow' : 'badge-green')),
            ]);
        }

        foreach ($donationsList as $d) {
            $logs->push([
                'time' => $d->created_at,
                'type' => 'donasi',
                'badge' => 'DONASI',
                'badge_class' => 'badge-orange',
                'detail' => "Bantuan Logistik: " . ($d->shelterNeed?->item_name ?? 'Paket Bantuan') . " (" . $d->quantity_donated . " unit) untuk posko",
                'pic' => $d->donor?->fullname ?? 'Donatur',
                'status' => $d->status == 'delivered' ? 'Diterima' : ($d->status == 'accepted' ? 'Disetujui' : 'Pending'),
                'status_class' => $d->status == 'delivered' ? 'badge-green' : ($d->status == 'accepted' ? 'badge-blue' : 'badge-yellow'),
            ]);
        }

        $activityLog = $logs->sortByDesc('time')->take(20);

        // Recent SOS for activity log (merged)
        $recentSos        = SosRequest::with('user')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return view('shared.dashboard', compact(
            'titikBanjir',
            'wargaTerdampak',
            'sosMenunggu',
            'poskoAktif',
            'shelters',
            'waterGates',
            'latestSos',
            'activityLog',
            'recentSos'
        ));
    }

    /**
     * Peta Evakuasi — accessible by all roles.
     * Figma node: 174:2
     */
    public function petaEvakuasi()
    {
        $shelters   = Shelter::all();
        $waterGates = WaterGate::orderBy('danger_status', 'desc')->get();
        $reports    = FloodReport::where('verification_status', 'verified')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();

        $totalLaporanBaru = FloodReport::whereDate('created_at', today())->count();

        return view('shared.peta-evakuasi', compact(
            'shelters',
            'waterGates',
            'reports',
            'totalLaporanBaru'
        ));
    }

    /**
     * Data Pintu Air (TMA) Real-Time — accessible by all roles.
     * Figma node: 163:3941
     */
    public function dataPintuAir()
    {
        $waterGates = WaterGate::orderBy('danger_status', 'desc')->get();

        // Summary stats
        $totalGates   = $waterGates->count();
        $siaga1Count  = $waterGates->where('danger_status', 'Siaga_1')->count();
        $siaga2Count  = $waterGates->where('danger_status', 'Siaga_2')->count();
        $siaga3Count  = $waterGates->where('danger_status', 'Siaga_3')->count();
        $normalCount  = $waterGates->where('danger_status', 'Normal')->count();

        // Highest level gate (for the featured chart + table header)
        $featuredGate = $waterGates->sortByDesc('water_level_cm')->first();

        // Chart data — each gate becomes a dataset with a simple 12-point simulated reading
        // using real current level as the peak value (since no readings table exists yet).
        // Time labels are the last 12 two-hour slots from midnight.
        $chartLabels  = ['00:00','02:00','04:00','06:00','08:00','10:00','12:00','14:00','16:00','18:00','20:00','22:00'];

        $chartDatasets = [];
        $colors = ['#ba1a1a','#f59e0b','#006a60','#3b82f6','#8b5cf6','#ec4899'];
        $i = 0;
        foreach ($waterGates->take(4) as $gate) {
            $peak     = $gate->water_level_cm;
            $base     = max(20, round($peak * 0.35));
            // Build a rising curve towards the peak at step 9 (18:00) then slight drop
            $curve = [];
            for ($h = 0; $h < 12; $h++) {
                $factor = $h <= 9 ? ($h / 9) : (1 - ($h - 9) / 6 * 0.3);
                $curve[] = max($base, round($base + ($peak - $base) * $factor));
            }
            $color = $colors[$i % count($colors)];
            $chartDatasets[] = [
                'label'           => $gate->gate_name,
                'data'            => $curve,
                'borderColor'     => $color,
                'backgroundColor' => $i === 0 ? str_replace(')', ', 0.06)', str_replace('rgb', 'rgba', $color)) : 'transparent',
                'borderWidth'     => $i === 0 ? 3 : 2,
                'tension'         => 0.35,
                'fill'            => $i === 0,
            ];
            $i++;
        }

        // Automatic alert log — built from real DB records
        $alertLog = collect();

        // Siaga-1 gates → critical alerts
        foreach ($waterGates->where('danger_status', 'Siaga_1') as $g) {
            $alertLog->push([
                'type'  => 'red',
                'icon'  => 'alert-octagon',
                'title' => 'SIAGA 1 — ' . $g->gate_name,
                'text'  => $g->river_name . ' menembus batas Siaga 1 (' . $g->water_level_cm . ' cm). Warga harus siap mengungsi!',
                'time'  => $g->last_updated ? \Carbon\Carbon::parse($g->last_updated)->format('H:i') . ' WIB' : 'Baru',
            ]);
        }

        // Siaga-2 gates → warnings
        foreach ($waterGates->where('danger_status', 'Siaga_2') as $g) {
            $alertLog->push([
                'type'  => 'orange',
                'icon'  => 'alert-triangle',
                'title' => 'ALERT — ' . $g->gate_name,
                'text'  => $g->river_name . ' menembus batas Siaga 2 (' . $g->water_level_cm . ' cm). Status waspada.',
                'time'  => $g->last_updated ? \Carbon\Carbon::parse($g->last_updated)->format('H:i') . ' WIB' : 'Baru',
            ]);
        }

        // Recent SOS waiting → info alerts
        $recentSos = SosRequest::where('status', 'waiting')->latest()->take(2)->get();
        foreach ($recentSos as $s) {
            $alertLog->push([
                'type'  => 'red',
                'icon'  => 'smartphone',
                'title' => 'SOS DARURAT',
                'text'  => 'Evakuasi ' . $s->people_trapped . ' warga terjebak di ' . ($s->user?->kelurahan ?? 'Bekasi') . '. Relawan diminta segera bergerak.',
                'time'  => $s->created_at->format('H:i') . ' WIB',
            ]);
        }

        // Recent verified flood reports → info
        $recentReports = FloodReport::where('verification_status', 'verified')->latest()->take(2)->get();
        foreach ($recentReports as $r) {
            $alertLog->push([
                'type'  => '',
                'icon'  => 'info',
                'title' => 'LAPORAN TERVERIFIKASI',
                'text'  => 'Genangan di ' . $r->street_name . ' (Tinggi Air: ' . $r->water_height_cm . ' cm) sudah terverifikasi.',
                'time'  => $r->created_at->format('H:i') . ' WIB',
            ]);
        }

        // If nothing, show a positive notice
        if ($alertLog->isEmpty()) {
            $alertLog->push([
                'type'  => '',
                'icon'  => 'check-circle',
                'title' => 'SEMUA NORMAL',
                'text'  => 'Seluruh pintu air terpantau dalam kondisi aman. Tidak ada peringatan aktif.',
                'time'  => now()->format('H:i') . ' WIB',
            ]);
        }

        return view('shared.data-pintu-air', compact(
            'waterGates',
            'chartLabels',
            'chartDatasets',
            'featuredGate',
            'totalGates',
            'siaga1Count',
            'siaga2Count',
            'siaga3Count',
            'normalCount',
            'alertLog'
        ));
    }


    /**
     * Posko Pengungsian — accessible by all roles.
     * Figma node: 187:2
     */
    public function posko(Request $request)
    {
        $filter   = $request->get('filter', 'all');

        $query = Shelter::query();

        if ($filter === 'available') {
            $query->where('status', 'active');
        } elseif ($filter === 'mck') {
            $query->where('has_toilet_facilities', 'Yes');
        }

        $shelters = $query->with('shelterNeeds')->orderByRaw("FIELD(status, 'active', 'full', 'closed')")->get();

        $stats = [
            'poskoAktif'      => Shelter::whereIn('status', ['active', 'full'])->count(),
            'totalPengungsi'  => Shelter::sum('current_occupants'),
            'poskoTersedia'   => Shelter::where('status', 'active')->count(),
            'statusKritis'    => Shelter::where('status', 'full')->count(),
        ];

        return view('shared.posko', compact('shelters', 'stats', 'filter'));
    }

    /**
     * Export water gate (TMA) data to CSV.
     */
    public function exportWaterGates()
    {
        $waterGates = WaterGate::orderBy('danger_status', 'desc')->get();
        $fileName = 'tma_recap_' . time() . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID', 'Nama Pintu Air', 'Nama Sungai', 'Tinggi Muka Air (cm)', 'Status Bahaya', 'Terakhir Diperbarui'];

        $callback = function() use($waterGates, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($waterGates as $gate) {
                fputcsv($file, [
                    $gate->gate_id,
                    $gate->gate_name,
                    $gate->river_name,
                    $gate->water_level_cm,
                    $gate->danger_status,
                    $gate->last_updated ? $gate->last_updated->format('Y-m-d H:i:s') : '-'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export flood reports to CSV.
     */
    public function exportLaporan()
    {
        $reports = FloodReport::with('user')
            ->where('verification_status', 'verified')
            ->orderBy('created_at', 'desc')
            ->get();

        $fileName = 'flood_reports_' . time() . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID Laporan', 'Pelapor', 'Tinggi Air (cm)', 'Nama Jalan / Lokasi', 'Status Verifikasi', 'Tanggal Dilaporkan'];

        $callback = function() use($reports, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($reports as $report) {
                fputcsv($file, [
                    $report->report_id,
                    $report->user ? $report->user->fullname : 'Anonim',
                    $report->water_height_cm,
                    $report->street_name,
                    $report->verification_status,
                    $report->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
