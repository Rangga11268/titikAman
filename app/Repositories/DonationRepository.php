<?php

namespace App\Repositories;

use App\Models\Donation;
use Illuminate\Database\Eloquent\Collection;

class DonationRepository
{
    /**
     * Create a new donation.
     *
     * @param array $data
     * @return Donation
     */
    public function create(array $data): Donation
    {
        return Donation::create($data);
    }

    /**
     * Find a donation by ID.
     *
     * @param int $donationId
     * @return Donation|null
     */
    public function find(int $donationId): ?Donation
    {
        return Donation::with(['donor', 'shelterNeed', 'shelterNeed.shelter'])->find($donationId);
    }

    /**
     * Get donations for a specific shelter.
     *
     * @param int $shelterId
     * @return Collection
     */
    public function getDonationsByShelterId(int $shelterId): Collection
    {
        return Donation::with(['donor', 'shelterNeed'])
            ->whereHas('shelterNeed', function ($query) use ($shelterId) {
                $query->where('shelter_id', $shelterId);
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
