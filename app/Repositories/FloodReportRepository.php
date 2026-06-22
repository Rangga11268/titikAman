<?php

namespace App\Repositories;

use App\Models\FloodReport;
use Illuminate\Database\Eloquent\Collection;

class FloodReportRepository
{
    /**
     * Create a new flood report.
     *
     * @param array $data
     * @return FloodReport
     */
    public function create(array $data): FloodReport
    {
        return FloodReport::create($data);
    }

    /**
     * Get all verified flood reports.
     *
     * @return Collection
     */
    public function getVerifiedReports(): Collection
    {
        return FloodReport::with('user:user_id,fullname,phone')
            ->where('verification_status', 'verified')
            ->get();
    }

    /**
     * Get all flood reports submitted by a specific user.
     *
     * @param int $userId
     * @return Collection
     */
    public function getReportsByUserId(int $userId): Collection
    {
        return FloodReport::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
