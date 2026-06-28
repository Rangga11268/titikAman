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

        // Pendaftar Tim (Anggota Relawan yang baru mendaftar)
        $pendaftarTim = \App\Models\User::where('role', 'Relawan')->where('status', 'pending')->orderBy('created_at', 'desc')->get();

        // Anggota Tim Aktif (yang sudah di-approve), grouped by team (kecamatan)
        $anggotaTim = \App\Models\User::where('role', 'Relawan')
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function($user) {
                return $user->kecamatan ? 'Tim ' . $user->kecamatan : 'Tim Reguler';
            });

        return view('relawan.dashboard', compact(
            'activeMission',
            'waitingSos',
            'sosAntriCount',
            'highPrioritySos',
            'misiAktifku',
            'misiSelesaiCount',
            'completedMissions',
            'avgResponseMinutes',
            'pendaftarTim',
            'anggotaTim'
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
            'volunteer_id' => 'nullable|integer|exists:users,user_id',
        ]);

        try {
            $assignedVolunteerId = $request->volunteer_id ?? auth()->id();
            $mission = $this->rescueMissionService->acceptMission(
                (int) $request->sos_id,
                $assignedVolunteerId
            );
            
            $mission->load(['sosRequest.user']);
            $volunteer = \App\Models\User::find($assignedVolunteerId);
            $sos = $mission->sosRequest;
            $pelapor = $sos->user->fullname ?? 'Warga';
            $lokasi = ($sos->user->kelurahan ?? '') . ', ' . ($sos->user->kecamatan ?? '');
            $mapsLink = "https://maps.google.com/maps?q={$sos->latitude},{$sos->longitude}";
            
            $waMessage = "🚨 *DARURAT SOS!* Segera meluncur ke lokasi.\nPelapor: {$pelapor}\nLokasi: {$lokasi}\nPrioritas: " . strtoupper($sos->priority_level) . "\nGoogle Maps: {$mapsLink}";
            $waUrl = "https://wa.me/" . preg_replace('/[^0-9]/', '', $volunteer->phone) . "?text=" . urlencode($waMessage);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Misi evakuasi berhasil diterima! Silakan menuju lokasi korban.',
                    'mission' => $mission,
                    'wa_url' => $waUrl
                ]);
            }

            return redirect()
                ->route('relawan.dashboard')
                ->with('success', 'Misi evakuasi berhasil ditugaskan!')
                ->with('wa_url', $waUrl)
                ->with('wa_name', $volunteer->fullname);
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

    /**
     * Approve a pending volunteer member.
     */
    public function approveMember($id)
    {
        $user = \App\Models\User::where('role', 'Relawan')->where('status', 'pending')->findOrFail($id);
        $user->status = 'approved';
        $user->save();

        return redirect()->route('relawan.dashboard')->with('success', 'Anggota tim berhasil disetujui.');
    }

    /**
     * Reject a pending volunteer member.
     */
    public function rejectMember($id)
    {
        $user = \App\Models\User::where('role', 'Relawan')->where('status', 'pending')->findOrFail($id);
        $user->status = 'rejected';
        $user->save();

        return redirect()->route('relawan.dashboard')->with('success', 'Anggota tim berhasil ditolak.');
    }
}
