<?php

namespace App\Http\Controllers;

use App\Repositories\SosRepository;
use App\Repositories\RescueMissionRepository;
use App\Services\RescueMissionService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Exception;

class RelawanController extends Controller
{
    protected $sosRepository;
    protected $rescueMissionRepository;
    protected $rescueMissionService;

    // WhatsApp Group Links per Team
    private $waGroupLinks = [
        'teams' => [
            'Bekasi Timur'    => 'https://chat.whatsapp.com/INVITE_TIM_BEKASTIMUR',
            'Bekasi Selatan'  => 'https://chat.whatsapp.com/INVITE_TIM_BEKASISELATAN',
            'Bekasi Barat'    => 'https://chat.whatsapp.com/INVITE_TIM_BEKASIBARAT',
            'Bekasi Utara'    => 'https://chat.whatsapp.com/INVITE_TIM_BEKASIUTARA',
            'Jatiasih'        => 'https://chat.whatsapp.com/INVITE_TIM_JATIASIH',
            'Rawalumbu'       => 'https://chat.whatsapp.com/INVITE_TIM_RAWALUMBU',
            'Pondok Gede'     => 'https://chat.whatsapp.com/INVITE_TIM_PONDOKGEDE',
            'Mustikajaya'     => 'https://chat.whatsapp.com/INVITE_TIM_MUSTIKAJAYA',
        ],
        'backup' => 'https://chat.whatsapp.com/INVITE_GRUP_GABUNGAN',
    ];

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
        // Only Admin Relawan can access this dashboard
        if (auth()->user()->role !== 'Admin_Relawan') {
            return redirect()->route('dashboard');
        }

        $volunteerId = auth()->id();
        
        // Fetch all active missions for the dispatcher
        $activeMissions = $this->rescueMissionRepository->getAllActiveMissions();
        
        // Fetch waiting SOS requests (if no active mission, or for the map markers)
        $waitingSos = $this->sosRepository->getWaitingRequests();

        // Statistics for stat cards
        $sosAntriCount     = $waitingSos->count();
        $highPrioritySos   = $waitingSos->where('priority_level', 'high')->count();
        $misiAktifku       = $activeMissions->count();
        $misiSelesaiCount  = $this->rescueMissionRepository->getAllCompletedMissionsCount();
        $totalSosHariIni   = \App\Models\SosRequest::whereDate('created_at', today())->count();

        // All missions for history table (all time, ordered by most recent)
        $completedMissions = $this->rescueMissionRepository->getAllMissions();
        $completedMissionsDisplay = $completedMissions->take(10);
        $totalMissionsCount = $completedMissions->count();

        // Average response time (minutes) — computed in PHP, not in Blade
        $avgResponseMinutes = 0;
        $completedOnly = $completedMissions->filter(fn($m) => $m->resolved_at !== null);
        if ($misiSelesaiCount > 0 && $completedOnly->isNotEmpty()) {
            $totalMinutes = $completedOnly->sum(function ($m) {
                return $m->resolved_at->diffInMinutes($m->created_at);
            });
            $avgResponseMinutes = (int) round($totalMinutes / $misiSelesaiCount);
        }

        // Pendaftar Tim (Anggota Relawan yang baru mendaftar)
        $pendaftarTim = \App\Models\User::where('role', 'Relawan')->where('status', 'pending')->orderBy('created_at', 'desc')->get();

        // Anggota Tim Aktif (yang sudah di-approve), grouped by team (kecamatan)
        $anggotaTim = \App\Models\User::where('role', 'Relawan')
            ->where('status', 'approved')
            ->where('user_id', '!=', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function($user) {
                return $user->kecamatan ? 'Tim ' . $user->kecamatan : 'Tim Reguler';
            });

        // Get array of active volunteer IDs (busy teams)
        $activeVolunteerIds = $activeMissions->pluck('volunteer_id')->toArray();

        // Total registered relawan (Admin_Relawan + approved Relawan)
        $totalRelawan = \App\Models\User::whereIn('role', ['Admin_Relawan', 'Relawan'])
            ->where('status', 'approved')
            ->count();

        // Teams (Ketua Tim / Lead berdasarkan kecamatan)
        $teams = \App\Models\User::where('role', 'Admin_Relawan')
            ->where('user_id', '!=', auth()->id())
            ->get()
            ->map(fn($u) => [
                'id' => $u->user_id,
                'name' => $u->fullname,
                'kecamatan' => $u->kecamatan,
                'phone' => $u->phone,
                'label' => 'Tim ' . ($u->kecamatan ?? 'Reguler') . ' (Lead: ' . $u->fullname . ')',
            ]);

        // Fetch active shelters and flood reports for map
        $activeShelters = \App\Models\Shelter::whereIn('status', ['active', 'full'])->get();
        $verifiedReports = \App\Models\FloodReport::where('verification_status', 'verified')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        return view('relawan.dashboard', compact(
            'activeMissions',
            'waitingSos',
            'sosAntriCount',
            'highPrioritySos',
            'misiAktifku',
            'misiSelesaiCount',
            'avgResponseMinutes',
            'totalRelawan',
            'totalSosHariIni',
            'completedMissions',
            'completedMissionsDisplay',
            'totalMissionsCount',
            'pendaftarTim',
            'anggotaTim',
            'activeVolunteerIds',
            'teams',
            'activeShelters',
            'verifiedReports'
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
            'volunteer_id' => 'required|integer|exists:users,user_id|different:' . auth()->id(),
        ]);

        try {
            $assignedVolunteerId = (int) $request->volunteer_id;
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
            $rawPhone = preg_replace('/[^0-9]/', '', $volunteer->phone);
            $volWaNumber = substr($rawPhone, 0, 1) === '0' ? '62' . substr($rawPhone, 1) : (substr($rawPhone, 0, 2) !== '62' ? '62' . $rawPhone : $rawPhone);
            $waUrl = $volWaNumber ? "https://wa.me/{$volWaNumber}?text=" . urlencode($waMessage) : '#';

            if ($request->wantsJson() || $request->ajax()) {
                  return response()->json([
                      'status'  => 'success',
                      'message' => 'Misi evakuasi berhasil diterima! Silakan menuju lokasi korban.',
                      'mission' => [
                          'volunteer_name' => $volunteer->fullname,
                          'team_info'      => $teamInfo ?? ('Tim ' . ($volunteer->kecamatan ?? 'Reguler')),
                          'pelapor'        => $pelapor,
                          'lokasi'         => $lokasi,
                          'maps'           => $mapsLink,
                          'wa_share_team'  => 'https://api.whatsapp.com/send?text=' . urlencode("🚨 *MISI EVAKUASI BARU*\nPelapor: {$pelapor}\nLokasi: {$lokasi}\nGoogle Maps: {$mapsLink}"),
                      ],
                      'wa_url'  => $waUrl,
                  ]);
              }

            $kec = $volunteer->kecamatan;
            $teamInfo = $kec ? 'Tim ' . $kec : 'Tim Reguler';

            $shareTeamText = "🚨 *MISI EVAKUASI BARU - {$teamInfo}*\nPelapor: {$pelapor}\nLokasi: {$lokasi}\nPrioritas: " . strtoupper($sos->priority_level) . "\nGoogle Maps: {$mapsLink}";
            $shareBackupText = "⚠️ *BUTUH BANTUAN BACKUP TIM!*\n{$teamInfo} sedang menangani SOS di {$lokasi}.\nPelapor: {$pelapor}\nGoogle Maps: {$mapsLink}";

            session([
                'wa_url' => $waUrl,
                'wa_name' => $volunteer->fullname,
                'wa_pelapor' => $pelapor,
                'wa_lokasi' => $lokasi,
                'wa_maps' => $mapsLink,
                'wa_share_team_url' => 'https://api.whatsapp.com/send?text=' . urlencode($shareTeamText),
                'wa_share_backup_url' => 'https://api.whatsapp.com/send?text=' . urlencode($shareBackupText),
                'wa_team_info' => $teamInfo,
            ]);

            return redirect()
                ->route('relawan.dashboard')
                ->with('success', 'Misi evakuasi berhasil ditugaskan!');
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
     * Get full SOS queue data for realtime card rendering.
     * @return JsonResponse
     */
    public function getSosQueue(): JsonResponse
    {
        $waitingSos = $this->sosRepository->getWaitingRequests();

        $data = $waitingSos->map(function ($sos) {
            $priorityLabel = match($sos->priority_level) {
                'high'   => 'TINGGI',
                'medium' => 'SEDANG',
                default  => 'RENDAH',
            };
            $priorityClass = match($sos->priority_level) {
                'high'   => 'sos-priority-high',
                'medium' => 'sos-priority-medium',
                default  => 'sos-priority-low',
            };
            return [
                'id'             => $sos->sos_id,
                'status'         => $sos->status,
                'priority_level' => $sos->priority_level,
                'priority_label' => $priorityLabel,
                'priority_class' => $priorityClass,
                'people_trapped' => $sos->people_trapped,
                'latitude'       => $sos->latitude,
                'longitude'      => $sos->longitude,
                'kelurahan'      => $sos->user->kelurahan ?? '-',
                'kecamatan'      => $sos->user->kecamatan ?? '-',
                'phone'          => $sos->user->phone ?? '-',
                'fullname'       => $sos->user->fullname ?? 'Warga',
                'description'    => $sos->description ?? '',
                'created_at'     => $sos->created_at->diffForHumans(),
            ];
        });

        return response()->json([
            'count' => $data->count(),
            'items' => $data,
        ]);
    }

    /**
     * Approve a pending volunteer member.
     */
    public function approveMember($id)
    {
        $user = \App\Models\User::where('role', 'Relawan')->where('status', 'pending')->findOrFail($id);
        $user->status = 'approved';
        $user->save();

        $kec = $user->kecamatan;
        $teamName = $kec ? 'Tim ' . $kec : 'Tim Reguler';
        $groupLink = $this->waGroupLinks['teams'][$kec] ?? $this->waGroupLinks['backup'];

        // WhatsApp link to send group invite to the new member
        $raw = preg_replace('/[^0-9]/', '', $user->phone);
        // Convert to international format for wa.me
        if (substr($raw, 0, 1) === '0') {
            $waNumber = '62' . substr($raw, 1);
        } elseif (substr($raw, 0, 2) !== '62') {
            $waNumber = '62' . $raw;
        } else {
            $waNumber = $raw;
        }
        $waText = "Halo *{$user->fullname}*, akun Relawan Anda telah disetujui! 🎉\n\nAnda terdaftar sebagai anggota *{$teamName}*.\n\nBergabung ke grup tim melalui link berikut:\n{$groupLink}\n\nSalam,\nTim Admin Relawan TitikAman";
        $waUrl = $waNumber ? "https://wa.me/{$waNumber}?text=" . urlencode($waText) : '#';

        session([
            'approved_member_name' => $user->fullname,
            'approved_member_team' => $teamName,
            'approved_wa_group_link' => $groupLink,
            'approved_wa_send_url' => $waUrl,
        ]);

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

    /**
     * Export all missions to CSV.
     */
    public function exportMissions()
    {
        $missions = $this->rescueMissionRepository->getAllMissions();
        $fileName = 'riwayat_misi_' . time() . '.csv';

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['ID Misi', 'ID SOS', 'Pelapor', 'No. HP Pelapor', 'Lokasi', 'Jumlah Orang', 'Kelompok Rentan', 'Prioritas', 'Deskripsi', 'Relawan Ditugaskan', 'No. HP Relawan', 'Ditugaskan Pada', 'Selesai Pada', 'Durasi (Menit)', 'Status'];

        $callback = function () use ($missions, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($missions as $mission) {
                $sos = $mission->sosRequest;
                $user = $sos?->user;
                $volunteer = $mission->volunteer;
                $durasi = $mission->resolved_at ? (int) $mission->resolved_at->diffInMinutes($mission->created_at) : '-';
                $status = $mission->resolved_at ? 'Selesai' : 'Berjalan';

                fputcsv($file, [
                    $mission->mission_id,
                    $mission->sos_id,
                    $user->fullname ?? '-',
                    $user->phone ?? '-',
                    ($user->kelurahan ?? '') . ', ' . ($user->kecamatan ?? ''),
                    $sos->people_trapped ?? 0,
                    $sos->vulnerable_groups_count ?? 0,
                    $sos->priority_level ?? '-',
                    $sos->description ?? '-',
                    $volunteer->fullname ?? '-',
                    $volunteer->phone ?? '-',
                    $mission->assigned_at ? $mission->assigned_at->format('Y-m-d H:i:s') : '-',
                    $mission->resolved_at ? $mission->resolved_at->format('Y-m-d H:i:s') : '-',
                    $durasi,
                    $status,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Dismiss the WhatsApp notification banner by clearing session data.
     */
    public function dismissWa()
    {
        session()->forget(['wa_url', 'wa_name', 'wa_pelapor', 'wa_lokasi', 'wa_maps', 'wa_share_team_url', 'wa_share_backup_url', 'wa_team_info']);
        return redirect()->route('relawan.dashboard');
    }

    /**
     * Dismiss the approval notification banner.
     */
    public function dismissApproval()
    {
        session()->forget(['approved_member_name', 'approved_member_team', 'approved_wa_group_link']);
        return redirect()->route('relawan.dashboard');
    }

    /**
     * Update anggota tim (keahlian, organisasi, kecamatan/kelurahan).
     */
    public function updateMember(Request $request, $id)
    {
        $member = \App\Models\User::where('role', 'Relawan')->where('status', 'approved')->findOrFail($id);

        $request->validate([
            'keahlian' => 'nullable|string|max:255',
            'organisasi' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
            'kelurahan' => 'nullable|string|max:100',
        ]);

        $member->update($request->only(['keahlian', 'organisasi', 'kecamatan', 'kelurahan']));

        return redirect()->route('relawan.dashboard')->with('success', 'Data anggota tim berhasil diperbarui.');
    }

    /**
     * Pindahkan anggota ke tim lain (ubah kecamatan/kelurahan).
     */
    public function moveMember(Request $request, $id)
    {
        $member = \App\Models\User::where('role', 'Relawan')->where('status', 'approved')->findOrFail($id);

        $request->validate([
            'kecamatan' => 'required|string|max:100',
            'kelurahan' => 'required|string|max:100',
        ]);

        $member->update($request->only(['kecamatan', 'kelurahan']));

        return redirect()->route('relawan.dashboard')->with('success', 'Anggota berhasil dipindahkan ke tim ' . $request->kecamatan . '.');
    }

    /**
     * Hapus (nonaktifkan) anggota dari tim.
     */
    public function removeMember($id)
    {
        $member = \App\Models\User::where('role', 'Relawan')->where('status', 'approved')->findOrFail($id);
        $member->status = 'rejected';
        $member->save();

        return redirect()->route('relawan.dashboard')->with('success', 'Anggota berhasil dihapus dari tim.');
    }

    /**
     * Tambah anggota baru secara manual.
     */
    public function addMember(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string|max:100',
            'phone' => 'required|string|max:20|unique:users,phone',
            'email' => 'nullable|email|max:100|unique:users,email',
            'password' => 'required|string|min:8',
            'kecamatan' => [
                'required',
                'string',
                'max:100',
                Rule::in([
                    'Pondok Gede', 'Jatiasih', 'Bekasi Timur', 'Bekasi Selatan',
                    'Bekasi Barat', 'Bekasi Utara', 'Rawalumbu', 'Mustikajaya',
                    'Bantargebang', 'Medansatria', 'Jatisampurna',
                ]),
            ],
            'kelurahan' => 'required|string|max:100',
            'keahlian' => 'nullable|string|max:255',
            'organisasi' => 'nullable|string|max:100',
        ], [
            'fullname.required' => 'Nama lengkap wajib diisi.',
            'phone.required' => 'Nomor HP wajib diisi.',
            'phone.unique' => 'Nomor HP sudah terdaftar.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'kecamatan.required' => 'Kecamatan domisili wajib dipilih.',
            'kecamatan.in' => 'Kecamatan harus berada di wilayah Kota Bekasi.',
            'kelurahan.required' => 'Kelurahan domisili wajib dipilih.',
        ]);

        $user = User::create([
            'fullname' => $request->fullname,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'Relawan',
            'kecamatan' => $request->kecamatan,
            'kelurahan' => $request->kelurahan,
            'keahlian' => $request->keahlian,
            'organisasi' => $request->organisasi,
            'status' => 'approved',
        ]);

        $kec = $user->kecamatan;
        $teamName = $kec ? 'Tim ' . $kec : 'Tim Reguler';
        $groupLink = $this->waGroupLinks['teams'][$kec] ?? $this->waGroupLinks['backup'];

        $raw = preg_replace('/[^0-9]/', '', $user->phone);
        if (substr($raw, 0, 1) === '0') {
            $waNumber = '62' . substr($raw, 1);
        } elseif (substr($raw, 0, 2) !== '62') {
            $waNumber = '62' . $raw;
        } else {
            $waNumber = $raw;
        }
        $waText = "Halo *{$user->fullname}*, akun Relawan Anda telah dibuat! 🎉\n\nAnda terdaftar sebagai anggota *{$teamName}*.\n\nBergabung ke grup tim melalui link berikut:\n{$groupLink}\n\nSalam,\nTim Admin Relawan TitikAman";
        $waUrl = $waNumber ? "https://wa.me/{$waNumber}?text=" . urlencode($waText) : '#';

        session([
            'approved_member_name' => $user->fullname,
            'approved_member_team' => $teamName,
            'approved_wa_group_link' => $groupLink,
            'approved_wa_send_url' => $waUrl,
        ]);

        return redirect()->route('relawan.dashboard')->with('success', 'Anggota baru berhasil ditambahkan secara manual.');
    }
}
