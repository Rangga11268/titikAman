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
        // Admin / BPBD redirect to admin dashboard
        if (auth()->user()->role === 'Admin_BPBD') {
            return redirect()->route('admin.dashboard');
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

        // Flood Reports for Map
        $verifiedReports = FloodReport::where('verification_status', 'verified')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        // Logistics Stats for Dashboard Widget (Aggregate by parsing item_name)
        $allNeeds = \App\Models\ShelterNeed::all();
        $statsArray = [];
        foreach ($allNeeds as $need) {
            $itemLower = strtolower($need->item_name);
            $category = 'Lain-lain';
            if (str_contains($itemLower, 'makan') || str_contains($itemLower, 'beras') || str_contains($itemLower, 'porsi') || str_contains($itemLower, 'indomie') || str_contains($itemLower, 'roti')) {
                $category = 'Makanan Siap Saji';
            } elseif (str_contains($itemLower, 'obat') || str_contains($itemLower, 'medis') || str_contains($itemLower, 'masker') || str_contains($itemLower, 'p3k')) {
                $category = 'Obat-obatan Dasar';
            } elseif (str_contains($itemLower, 'selimut') || str_contains($itemLower, 'pakaian') || str_contains($itemLower, 'baju') || str_contains($itemLower, 'tikar') || str_contains($itemLower, 'kasur')) {
                $category = 'Selimut & Kasur Lipat';
            } elseif (str_contains($itemLower, 'susu') || str_contains($itemLower, 'bayi') || str_contains($itemLower, 'pampers') || str_contains($itemLower, 'popok')) {
                $category = 'Susu Formula & Balita';
            } elseif (str_contains($itemLower, 'pembalut') || str_contains($itemLower, 'toilet') || str_contains($itemLower, 'sabun') || str_contains($itemLower, 'shampoo')) {
                $category = 'Kebutuhan Wanita';
            } elseif (str_contains($itemLower, 'air') || str_contains($itemLower, 'mineral') || str_contains($itemLower, 'minum')) {
                $category = 'Air Bersih';
            }

            if (!isset($statsArray[$category])) {
                $statsArray[$category] = ['category' => $category, 'total_need' => 0, 'total_fulfilled' => 0];
            }
            $statsArray[$category]['total_need'] += $need->quantity_need;
            $statsArray[$category]['total_fulfilled'] += $need->quantity_fulfilled;
        }

        usort($statsArray, function($a, $b) {
            return $b['total_need'] <=> $a['total_need'];
        });

        $logistikStats = collect(array_slice($statsArray, 0, 4))->map(function($item) {
            return (object) $item;
        });

        return view('shared.dashboard', compact(
            'titikBanjir',
            'wargaTerdampak',
            'sosMenunggu',
            'poskoAktif',
            'shelters',
            'waterGates',
            'latestSos',
            'activityLog',
            'recentSos',
            'verifiedReports',
            'logistikStats'
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
            ->where('water_height_cm', '>', 0)
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
        WaterGate::syncAllDangerStatuses();

        $waterGates = WaterGate::orderBy('danger_status', 'desc')->get();

        // Summary stats
        $totalGates   = $waterGates->count();
        $siaga1Count  = $waterGates->where('danger_status', 'Siaga_1')->count();
        $siaga2Count  = $waterGates->where('danger_status', 'Siaga_2')->count();
        $siaga3Count  = $waterGates->where('danger_status', 'Siaga_3')->count();
        $normalCount  = $waterGates->where('danger_status', 'Normal')->count();

        // Highest level gate (for the featured chart + table header)
        $featuredGate = $waterGates->sortByDesc('water_level_cm')->first();

        $chartLabels24h  = ['00:00','02:00','04:00','06:00','08:00','10:00','12:00','14:00','16:00','18:00','20:00','22:00'];
        $chartLabels7d   = collect(range(6, 0))
            ->map(fn ($daysAgo) => now()->subDays($daysAgo)->format('d M'))
            ->values()
            ->all();

        $chartDatasets24h = $this->buildTmaChartDatasets($waterGates->take(4), 12);
        $chartDatasets7d  = $this->buildTmaChartDatasets($waterGates->take(4), 7);

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
            'chartLabels24h',
            'chartDatasets24h',
            'chartLabels7d',
            'chartDatasets7d',
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
     * Bangun dataset grafik TMA simulasi dari level saat ini (tanpa tabel histori).
     */
    protected function buildTmaChartDatasets($waterGates, int $pointCount): array
    {
        $colors = ['#ba1a1a', '#f59e0b', '#006a60', '#3b82f6', '#8b5cf6', '#ec4899'];
        $datasets = [];
        $i = 0;

        foreach ($waterGates as $gate) {
            $peak = (float) $gate->water_level_cm;
            $base = max(20, round($peak * 0.35));
            $curve = [];

            for ($step = 0; $step < $pointCount; $step++) {
                if ($pointCount === 12) {
                    $factor = $step <= 9 ? ($step / 9) : (1 - ($step - 9) / 6 * 0.3);
                } else {
                    $factor = $pointCount > 1 ? ($step / ($pointCount - 1)) : 1;
                }

                $curve[] = max($base, round($base + ($peak - $base) * $factor));
            }

            $color = $colors[$i % count($colors)];
            $datasets[] = [
                'gateId'          => $gate->gate_id,
                'label'           => $gate->gate_name,
                'data'            => $curve,
                'borderColor'     => $color,
                'backgroundColor' => $i === 0 ? $color . '10' : 'transparent',
                'borderWidth'     => 2,
                'tension'         => 0.35,
                'fill'            => false,
                'hidden'          => false,
            ];
            $i++;
        }

        return $datasets;
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
