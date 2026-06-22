<?php

namespace App\Http\Controllers;

use App\Repositories\SosRepository;
use App\Repositories\RescueMissionRepository;
use App\Services\RescueMissionService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;

class RelawanController extends Controller
{
    protected $sosRepository;
    protected $rescueMissionRepository;
    protected $rescueMissionService;

    public function __construct(
        SosRepository $sosRepository,
        RescueMissionRepository $rescueMissionRepository,
        RescueMissionService $rescueMissionService
    ) {
        $this->sosRepository = $sosRepository;
        $this->rescueMissionRepository = $rescueMissionRepository;
        $this->rescueMissionService = $rescueMissionService;
    }

    /**
     * Show the volunteer dashboard.
     */
    public function dashboard()
    {
        $volunteerId = auth()->id();
        
        // Fetch active mission for this volunteer
        $activeMission = $this->rescueMissionRepository->getActiveMissionByVolunteerId($volunteerId);
        
        // Fetch waiting SOS requests (if no active mission, or for the map markers)
        $waitingSos = $this->sosRepository->getWaitingRequests();

        // Statistics for stat cards
        $sosAntriCount     = $waitingSos->count();
        $highPrioritySos   = $waitingSos->where('priority_level', 'high')->count();
        $misiAktifku       = $activeMission ? 1 : 0;
        $misiSelesaiCount  = $this->rescueMissionRepository->getCompletedMissionCountByVolunteerId($volunteerId);
        $totalSosHariIni   = \App\Models\SosRequest::whereDate('created_at', today())->count();

        // Completed missions today for history table
        $completedMissions = $this->rescueMissionRepository->getCompletedMissionsByVolunteerId($volunteerId);

        // Average response time (minutes) — computed in PHP, not in Blade
        $avgResponseMinutes = 0;
        if ($misiSelesaiCount > 0 && $completedMissions->isNotEmpty()) {
            $totalMinutes = $completedMissions->sum(function ($m) {
                return $m->resolved_at->diffInMinutes($m->created_at);
            });
            $avgResponseMinutes = (int) round($totalMinutes / $misiSelesaiCount);
        }

        return view('relawan.dashboard', compact(
            'activeMission',
            'waitingSos',
            'sosAntriCount',
            'highPrioritySos',
            'misiAktifku',
            'misiSelesaiCount',
            'completedMissions',
            'avgResponseMinutes'
        ));
    }

    /**
     * Claim/Accept an SOS request as a rescue mission.
     *
     * @param Request $request
     * @return JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function acceptMission(Request $request)
    {
        $request->validate([
            'sos_id' => 'required|integer|exists:sos_requests,sos_id',
        ]);

        try {
            $mission = $this->rescueMissionService->acceptMission(
                (int) $request->sos_id,
                auth()->id()
            );

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Misi evakuasi berhasil diterima! Silakan menuju lokasi korban.',
                    'mission' => $mission->load(['sosRequest', 'sosRequest.user'])
                ]);
            }

            return redirect()
                ->route('relawan.dashboard')
                ->with('success', 'Misi evakuasi berhasil diterima! Peta rute telah diaktifkan.');
        } catch (Exception $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ], 422);
            }

            return redirect()
                ->route('relawan.dashboard')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Resolve/Complete an active rescue mission.
     *
     * @param Request $request
     * @param int $missionId
     * @return JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function completeMission(Request $request, int $missionId)
    {
        try {
            $this->rescueMissionService->completeMission($missionId);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Misi evakuasi berhasil diselesaikan. Terima kasih atas bantuan Anda!'
                ]);
            }

            return redirect()
                ->route('relawan.dashboard')
                ->with('success', 'Misi evakuasi berhasil diselesaikan. Korban telah aman di posko.');
        } catch (Exception $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ], 422);
            }

            return redirect()
                ->route('relawan.dashboard')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Get waiting SOS requests as JSON for AJAX polling.
     *
     * @return JsonResponse
     */
    public function getWaitingSosData(): JsonResponse
    {
        $waitingSos = $this->sosRepository->getWaitingRequests();
        return response()->json($waitingSos);
    }
}
