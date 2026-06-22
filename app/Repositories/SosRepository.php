<?php

namespace App\Repositories;

use App\Models\SosRequest;
use Illuminate\Database\Eloquent\Collection;

class SosRepository
{
    /**
     * Create a new SOS request.
     *
     * @param array $data
     * @return SosRequest
     */
    public function create(array $data): SosRequest
    {
        return SosRequest::create($data);
    }

    /**
     * Get active SOS request for a given user.
     *
     * @param int $userId
     * @return SosRequest|null
     */
    public function getActiveRequestByUserId(int $userId): ?SosRequest
    {
        return SosRequest::where('user_id', $userId)
            ->whereIn('status', ['waiting', 'assigned'])
            ->orderBy('created_at', 'desc')
            ->first();
    }

    /**
     * Get active SOS requests.
     *
     * @return Collection
     */
    public function getActiveRequests(): Collection
    {
        return SosRequest::with('user:user_id,fullname,phone')
            ->whereIn('status', ['waiting', 'assigned'])
            ->get();
    }

    /**
     * Get waiting SOS requests.
     *
     * @return Collection
     */
    public function getWaitingRequests(): Collection
    {
        return SosRequest::with('user:user_id,fullname,phone')
            ->where('status', 'waiting')
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
