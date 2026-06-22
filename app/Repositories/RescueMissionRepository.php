<?php

namespace App\Repositories;

use App\Models\RescueMission;

class RescueMissionRepository
{
    /**
     * Create a new rescue mission.
     *
     * @param array $data
     * @return RescueMission
     */
    public function create(array $data): RescueMission
    {
        return RescueMission::create($data);
    }

    /**
     * Find active rescue mission for a given volunteer.
     *
     * @param int $volunteerId
     * @return RescueMission|null
     */
    public function getActiveMissionByVolunteerId(int $volunteerId): ?RescueMission
    {
        return RescueMission::with(['sosRequest', 'sosRequest.user'])
            ->where('volunteer_id', $volunteerId)
            ->whereNull('resolved_at')
            ->first();
    }

    /**
     * Find a rescue mission by ID.
     *
     * @param int $missionId
     * @return RescueMission|null
     */
    public function find(int $missionId): ?RescueMission
    {
        return RescueMission::with(['sosRequest', 'sosRequest.user'])->find($missionId);
    }

    /**
     * Count completed missions for a given volunteer (today).
     *
     * @param int $volunteerId
     * @return int
     */
    public function getCompletedMissionCountByVolunteerId(int $volunteerId): int
    {
        return RescueMission::where('volunteer_id', $volunteerId)
            ->whereNotNull('resolved_at')
            ->whereDate('resolved_at', today())
            ->count();
    }

    /**
     * Get completed missions for a given volunteer (today), for history table.
     *
     * @param int $volunteerId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCompletedMissionsByVolunteerId(int $volunteerId)
    {
        return RescueMission::with(['sosRequest', 'sosRequest.user'])
            ->where('volunteer_id', $volunteerId)
            ->whereNotNull('resolved_at')
            ->whereDate('resolved_at', today())
            ->orderByDesc('resolved_at')
            ->get();
    }
}

