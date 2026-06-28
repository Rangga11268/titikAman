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
     * Get all active rescue missions (for Admin Relawan).
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllActiveMissions()
    {
        return RescueMission::with(['sosRequest', 'sosRequest.user', 'volunteer'])
            ->whereNull('resolved_at')
            ->get();
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
     * Count ALL completed missions (today).
     *
     * @return int
     */
    public function getAllCompletedMissionsCount(): int
    {
        return RescueMission::whereNotNull('resolved_at')
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
        return RescueMission::with(['sosRequest', 'sosRequest.user', 'volunteer'])
            ->where('volunteer_id', $volunteerId)
            ->whereNotNull('resolved_at')
            ->whereDate('resolved_at', today())
            ->orderByDesc('resolved_at')
            ->get();
    }

    /**
     * Get ALL completed missions (today), for history table.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllCompletedMissions()
    {
        return RescueMission::with(['sosRequest', 'sosRequest.user', 'volunteer'])
            ->whereNotNull('resolved_at')
            ->whereDate('resolved_at', today())
            ->orderByDesc('resolved_at')
            ->get();
    }

    /**
     * Get ALL missions (active & completed, all time), for history table.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllMissions()
    {
        return RescueMission::with(['sosRequest', 'sosRequest.user', 'volunteer'])
            ->orderByRaw('COALESCE(resolved_at, assigned_at, created_at) DESC')
            ->get();
    }
}

