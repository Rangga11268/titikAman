<?php

namespace App\Services;

use App\Repositories\DonationRepository;
use App\Repositories\ShelterNeedRepository;
use App\Models\Donation;
use App\Jobs\CompressDonationImageJob;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Exception;

class DonationService
{
    protected $donationRepository;
    protected $shelterNeedRepository;

    public function __construct(
        DonationRepository $donationRepository,
        ShelterNeedRepository $shelterNeedRepository
    ) {
        $this->donationRepository = $donationRepository;
        $this->shelterNeedRepository = $shelterNeedRepository;
    }

    /**
     * Submit a new donation request.
     *
     * @param array $data
     * @param UploadedFile $photo
     * @return Donation
     */
    public function submitDonation(array $data, UploadedFile $photo): Donation
    {
        // 1. Store the uploaded file in 'public/donations'
        $filename = uniqid() . '.' . $photo->getClientOriginalExtension();
        $directory = 'donations';
        $path = $photo->storeAs($directory, $filename, 'public');

        // 2. Dispatch background compression job
        CompressDonationImageJob::dispatch($path);

        // 3. Prepare donation data
        $data['proof_photo'] = $path;
        $data['status'] = 'pending'; // Default status is pending
        $data['donated_at'] = now();

        return $this->donationRepository->create($data);
    }

    /**
     * Verify donation and update status (e.g. accepted/delivered).
     *
     * @param int $donationId
     * @param string $status
     * @throws Exception
     */
    public function verifyDonation(int $donationId, string $status): void
    {
        DB::transaction(function () use ($donationId, $status) {
            $donation = $this->donationRepository->find($donationId);

            if (!$donation) {
                throw new Exception("Data donasi tidak ditemukan.");
            }

            if ($donation->status === 'delivered') {
                throw new Exception("Donasi ini sudah diselesaikan/diterima sebelumnya.");
            }

            // Save old status to check if it's transitioning to delivered
            $oldStatus = $donation->status;
            
            // Update donation status
            $donation->status = $status;
            $donation->save();

            // If transitioning to delivered, update the quantity_fulfilled in shelter needs
            if ($status === 'delivered' && $oldStatus !== 'delivered') {
                $need = $donation->shelterNeed;
                if ($need) {
                    $need->quantity_fulfilled += $donation->quantity_donated;
                    
                    // Cap it to quantity_need just in case, or allow excess
                    if ($need->quantity_fulfilled > $need->quantity_need) {
                        // We can allow excess or cap. Let's just update as is.
                    }
                    $need->save();
                }
            }
        });
    }
}
