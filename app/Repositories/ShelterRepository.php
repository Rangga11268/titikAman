<?php

namespace App\Repositories;

use App\Models\Shelter;
use Illuminate\Database\Eloquent\Collection;

class ShelterRepository
{
    /**
     * Get all active and full shelters.
     *
     * @return Collection
     */
    public function getActiveShelters(): Collection
    {
        return Shelter::whereIn('status', ['active', 'full'])->get();
    }
}
