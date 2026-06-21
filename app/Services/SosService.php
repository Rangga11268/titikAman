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

        return $this->sosRepository->create($data);
    }
}
