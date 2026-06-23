<?php

namespace App\Http\Controllers;

use App\Http\Requests\LaporBanjirRequest;
use App\Http\Requests\SosRequestSubmit;
use App\Repositories\ShelterRepository;
use App\Repositories\FloodReportRepository;
use App\Services\FloodReportService;
use App\Services\SosService;
use Illuminate\Http\JsonResponse;

class WargaController extends Controller
{
    protected $shelterRepository;
    protected $floodReportRepository;
    protected $floodReportService;
    protected $sosService;

    public function __construct(
        ShelterRepository $shelterRepository,
        FloodReportRepository $floodReportRepository,
        FloodReportService $floodReportService,
        SosService $sosService
    ) {
        $this->shelterRepository = $shelterRepository;
        $this->floodReportRepository = $floodReportRepository;
        $this->floodReportService = $floodReportService;
        $this->sosService = $sosService;
    }

    /**
     * Show the citizen portal dashboard (peta utama).
     */
    public function dashboard()
    {
        $titikBanjir      = \App\Models\FloodReport::where('verification_status', 'verified')->count();
        $wargaTerdampak   = \App\Models\User::where('role', 'Warga')->count();
        $sosMenunggu      = \App\Models\SosRequest::where('status', 'waiting')->count();
        $poskoAktif       = \App\Models\Shelter::whereIn('status', ['active', 'full'])->count();

        $shelters         = \App\Models\Shelter::whereIn('status', ['active', 'full'])->get();
        $waterGates       = \App\Models\WaterGate::orderBy('danger_status', 'desc')->get();

        $latestSos        = \App\Models\SosRequest::with('user')
            ->where('status', 'waiting')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        // Recent flood reports, SOS requests, water gates, and donations for activity log
        $reportsList = \App\Models\FloodReport::with('user')->orderBy('created_at', 'desc')->take(10)->get();
        $sosRequestsList = \App\Models\SosRequest::with('user')->orderBy('created_at', 'desc')->take(10)->get();
        $waterGatesList = \App\Models\WaterGate::orderBy('last_updated', 'desc')->take(5)->get();
        $donationsList = \App\Models\Donation::with(['donor', 'shelterNeed'])->orderBy('created_at', 'desc')->take(10)->get();

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

        $recentSos        = \App\Models\SosRequest::with('user')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        $reports = $this->floodReportRepository->getVerifiedReports();

        return view('warga.dashboard', compact(
            'titikBanjir',
            'wargaTerdampak',
            'sosMenunggu',
            'poskoAktif',
            'shelters',
            'waterGates',
            'latestSos',
            'activityLog',
            'recentSos',
            'reports'
        ));
    }

    /**
     * Show the flood reporting form.
     */
    public function showLapor()
    {
        $myReports = $this->floodReportRepository->getReportsByUserId(auth()->id());
        return view('warga.lapor-banjir', compact('myReports'));
    }

    /**
     * Show the dedicated SOS page.
     */
    public function showSos()
    {
        $activeSos = $this->sosService->getActiveRequestByUserId(auth()->id());
        return view('warga.sos', compact('activeSos'));
    }

    /**
     * Submit a new flood report.
     */
    public function submitLapor(LaporBanjirRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        $this->floodReportService->createReport($data, $request->file('photo_evidence'));

        return redirect()
            ->route('warga.dashboard')
            ->with('success', 'Laporan genangan banjir Anda berhasil dikirim dan sedang menunggu verifikasi petugas.');
    }

    /**
     * Submit an emergency SOS signal via AJAX/fetch.
     */
    public function submitSos(SosRequestSubmit $request): JsonResponse
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        $this->sosService->createSos($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Sinyal darurat SOS berhasil dikirim! Tim penyelamat (SAR) telah menerima koordinat Anda dan sedang berkoordinasi.'
        ]);
    }
}
