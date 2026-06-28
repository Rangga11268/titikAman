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
            ->route('dashboard')
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

    /**
     * Update the location of an active SOS signal via AJAX.
     */
    public function updateSosLocation(\Illuminate\Http\Request $request): JsonResponse
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $activeSos = $this->sosService->getActiveRequestByUserId(auth()->id());
        
        if (!$activeSos) {
            return response()->json(['status' => 'error', 'message' => 'Tidak ada sinyal SOS aktif ditemukan.'], 404);
        }

        $activeSos->update([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Lokasi GPS terbaru berhasil diperbarui ke sistem SAR.'
        ]);
    }
}
