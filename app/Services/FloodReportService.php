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

        // Normalize checkbox values
        $data['listrik_padam'] = isset($data['listrik_padam']) ? (bool)$data['listrik_padam'] : false;
        $data['air_masih_naik'] = isset($data['air_masih_naik']) ? (bool)$data['air_masih_naik'] : false;
        $data['butuh_evakuasi'] = isset($data['butuh_evakuasi']) ? (bool)$data['butuh_evakuasi'] : false;
        $data['warga_terisolasi'] = isset($data['warga_terisolasi']) ? (bool)$data['warga_terisolasi'] : false;

        return $this->floodReportRepository->create($data);
    }
}
