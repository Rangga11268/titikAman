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
        $poskoAktif       = Shelter::whereIn('status', ['available', 'almost_full'])->count();

        // Shelters for map markers
        $shelters         = Shelter::whereIn('status', ['available', 'almost_full', 'full'])->get();

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

        // Group readings for chart (last 24h — using current data as mock)
        // In production, this would come from a water_gate_readings table
        $chartData = $waterGates->map(function ($gate) {
            return [
                'name'   => $gate->gate_name,
                'river'  => $gate->river_name,
                'level'  => $gate->water_level_cm,
                'status' => $gate->danger_status,
            ];
        });

        return view('shared.data-pintu-air', compact('waterGates', 'chartData'));
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
            $query->where('status', 'available');
        } elseif ($filter === 'mck') {
            $query->where('has_toilet_facilities', true);
        }

        $shelters = $query->orderByRaw("FIELD(status, 'available', 'almost_full', 'full')")->get();

        $stats = [
            'poskoAktif'      => Shelter::whereIn('status', ['available', 'almost_full'])->count(),
            'totalPengungsi'  => Shelter::sum('current_occupants'),
            'poskoTersedia'   => Shelter::where('status', 'available')->count(),
            'statusKritis'    => Shelter::where('status', 'full')->count(),
        ];

        return view('shared.posko', compact('shelters', 'stats', 'filter'));
    }

    /**
     * Submit donation from posko page.
     */
    public function submitPosko(Request $request)
    {
        $request->validate([
            'jenis_bantuan' => 'required|string|max:255',
            'keterangan'    => 'required|string|max:1000',
        ]);

        // Store as a donation record
        Donation::create([
            'donor_name'    => auth()->user()->fullname,
            'donation_type' => $request->jenis_bantuan,
            'description'   => $request->keterangan,
            'status'        => 'pending',
            'user_id'       => auth()->id(),
        ]);

        return redirect()->route('posko')->with('success', 'Donasi berhasil dicatat! Tim koordinasi akan segera menghubungi Anda.');
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
