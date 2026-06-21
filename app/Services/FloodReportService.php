<?php

namespace App\Services;

use App\Repositories\FloodReportRepository;
use App\Models\FloodReport;
use Illuminate\Http\UploadedFile;

class FloodReportService
{
    protected $floodReportRepository;

    public function __construct(FloodReportRepository $floodReportRepository)
    {
        $this->floodReportRepository = $floodReportRepository;
    }

    /**
     * Handle the business logic of creating a flood report.
     *
     * @param array $data
     * @param UploadedFile $photo
     * @return FloodReport
     */
    public function createReport(array $data, UploadedFile $photo): FloodReport
    {
        // Compress and save image
        $savedPath = ImageService::compressAndSave($photo, 'reports', 60);
        $data['photo_evidence'] = $savedPath;
        $data['verification_status'] = 'pending'; // Default status is pending

        return $this->floodReportRepository->create($data);
    }
}
