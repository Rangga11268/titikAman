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
        $poskoAktif       = \App\Models\Shelter::whereIn('status', ['available', 'almost_full'])->count();

        $shelters         = \App\Models\Shelter::whereIn('status', ['available', 'almost_full', 'full'])->get();
        $waterGates       = \App\Models\WaterGate::orderBy('danger_status', 'desc')->get();

        $latestSos        = \App\Models\SosRequest::with('user')
            ->where('status', 'waiting')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        $activityLog      = \App\Models\FloodReport::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

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
