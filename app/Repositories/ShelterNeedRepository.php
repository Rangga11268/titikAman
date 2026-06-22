<?php

namespace App\Repositories;

use App\Models\ShelterNeed;
use Illuminate\Database\Eloquent\Collection;

class ShelterNeedRepository
{
    /**
     * Create a new shelter need.
     *
     * @param array $data
     * @return ShelterNeed
     */
    public function create(array $data): ShelterNeed
    {
        return ShelterNeed::create($data);
    }

    /**
     * Find a shelter need by ID.
     *
     * @param int $needId
     * @return ShelterNeed|null
     */
    public function find(int $needId): ?ShelterNeed
    {
        return ShelterNeed::find($needId);
    }

    /**
     * Get all needs for a specific shelter.
     *
     * @param int $shelterId
     * @return Collection
     */
    public function getNeedsByShelterId(int $shelterId): Collection
    {
        return ShelterNeed::where('shelter_id', $shelterId)
            ->orderBy('urgency', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get all active unfilled needs across all shelters for the donation hub.
     *
     * @return Collection
     */
    public function getAllUnfulfilledNeeds(): Collection
    {
        return ShelterNeed::with('shelter')
            ->whereColumn('quantity_fulfilled', '<', 'quantity_need')
            ->whereHas('shelter', function ($query) {
                $query->whereIn('status', ['active', 'full']);
            })
            ->orderBy('urgency', 'desc')
            ->get();
    }
}
