<?php

namespace App\Services;

use App\Repositories\RescueMissionRepository;
use App\Repositories\SosRepository;
use App\Models\RescueMission;
use Illuminate\Support\Facades\DB;
use Exception;

class RescueMissionService
{
    protected $rescueMissionRepository;
    protected $sosRepository;

    public function __construct(
        RescueMissionRepository $rescueMissionRepository,
        SosRepository $sosRepository
    ) {
        $this->rescueMissionRepository = $rescueMissionRepository;
        $this->sosRepository = $sosRepository;
    }

    /**
     * Accept a waiting SOS request and assign it to a volunteer.
     *
     * @param int $sosId
     * @param int $volunteerId
     * @return RescueMission
     * @throws Exception
     */
    public function acceptMission(int $sosId, int $volunteerId): RescueMission
    {
        return DB::transaction(function () use ($sosId, $volunteerId) {
            // Fetch SOS request
            $sos = \App\Models\SosRequest::lockForUpdate()->find($sosId);
            
            if (!$sos) {
                throw new Exception("Sinyal SOS tidak ditemukan.");
            }

            if ($sos->status !== 'waiting') {
                throw new Exception("Sinyal SOS ini sudah ditangani oleh relawan lain.");
            }

            // Check if volunteer already has an active mission
            $activeMission = $this->rescueMissionRepository->getActiveMissionByVolunteerId($volunteerId);
            if ($activeMission) {
                throw new Exception("Anda masih memiliki misi penyelamatan aktif yang belum selesai.");
            }

            // Update SOS status
            $sos->status = 'assigned';
            $sos->save();

            // Create mission
            return $this->rescueMissionRepository->create([
                'sos_id' => $sosId,
                'volunteer_id' => $volunteerId,
                'assigned_at' => now(),
            ]);
        });
    }

    /**
     * Complete an active rescue mission.
     *
     * @param int $missionId
     * @return void
     * @throws Exception
     */
    public function completeMission(int $missionId): void
    {
        DB::transaction(function () use ($missionId) {
            $mission = $this->rescueMissionRepository->find($missionId);

            if (!$mission) {
                throw new Exception("Misi penyelamatan tidak ditemukan.");
            }

            if ($mission->resolved_at) {
                throw new Exception("Misi penyelamatan ini sudah diselesaikan sebelumnya.");
            }

            // Mark mission resolved
            $mission->resolved_at = now();
            $mission->save();

            // Update SOS status to completed
            $sos = $mission->sosRequest;
            if ($sos) {
                $sos->status = 'completed';
                $sos->save();
            }
        });
    }
}
