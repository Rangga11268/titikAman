<?php

namespace App\Services;

use App\Repositories\SosRepository;
use App\Models\SosRequest;

class SosService
{
    protected $sosRepository;

    public function __construct(SosRepository $sosRepository)
    {
        $this->sosRepository = $sosRepository;
    }

    /**
     * Handle business logic of submitting an SOS request.
     *
     * @param array $data
     * @return SosRequest
     */
    public function createSos(array $data): SosRequest
    {
        $user = \Illuminate\Support\Facades\Auth::user();

        // Pastikan user memiliki domisili di wilayah Bekasi
        $bekasiKecamatan = [
            'Pondok Gede', 'Jatiasih', 'Bekasi Timur', 'Bekasi Selatan',
            'Bekasi Barat', 'Bekasi Utara', 'Rawalumbu', 'Mustikajaya',
            'Bantargebang', 'Medansatria', 'Jatisampurna',
        ];
        if ($user && !in_array($user->kecamatan, $bekasiKecamatan)) {
            abort(403, 'Fitur SOS hanya tersedia untuk warga di wilayah Kota Bekasi dan sekitarnya.');
        }

        // Calculate priority automatically
        $peopleTrapped = (int) $data['people_trapped'];
        $vulnerableCount = (int) $data['vulnerable_groups_count'];

        if ($vulnerableCount > 0 || $peopleTrapped >= 5) {
            $priority = 'high';
        } elseif ($peopleTrapped >= 3) {
            $priority = 'medium';
        } else {
            $priority = 'low';
        }

        $data['priority_level'] = $priority;
        $data['status'] = 'waiting'; // Default status is waiting

        $sos = $this->sosRepository->create($data);

        // Broadcast the event
        event(new \App\Events\SosDispatched($sos));

        return $sos;
    }

    /**
     * Get active SOS request for a given user.
     *
     * @param int $userId
     * @return \App\Models\SosRequest|null
     */
    public function getActiveRequestByUserId(int $userId)
    {
        return $this->sosRepository->getActiveRequestByUserId($userId);
    }
}
