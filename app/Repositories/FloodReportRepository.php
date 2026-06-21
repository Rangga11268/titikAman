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
}
