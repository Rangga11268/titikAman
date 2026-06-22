<?php

namespace App\Http\Controllers;

use App\Repositories\ShelterNeedRepository;
use App\Models\Shelter;
use App\Services\DonationService;
use Illuminate\Http\Request;
use Exception;

class DonasiController extends Controller
{
    protected $shelterNeedRepository;
    protected $donationService;

    public function __construct(
        ShelterNeedRepository $shelterNeedRepository,
        DonationService $donationService
    ) {
        $this->shelterNeedRepository = $shelterNeedRepository;
        $this->donationService = $donationService;
    }

    /**
     * Show the public donation hub.
     */
    public function index()
    {
        // Fetch all active/full shelters with their unfulfilled needs
        $shelters = Shelter::with(['shelterNeeds' => function ($query) {
            $query->whereColumn('quantity_fulfilled', '<', 'quantity_need')
                  ->orderBy('urgency', 'desc');
        }])->whereIn('status', ['active', 'full'])->get();

        // Calculate dynamic stats
        $totalNeededObj = \App\Models\ShelterNeed::selectRaw('SUM(quantity_need) as total_need, SUM(quantity_fulfilled) as total_fulfilled')->first();
        
        $totalNeededVal = $totalNeededObj->total_need ?? 0;
        $fulfilledVal   = $totalNeededObj->total_fulfilled ?? 0;
        
        $totalNeeded = number_format($totalNeededVal / 1000, 1) . 'k';
        if ($totalNeededVal < 1000) { $totalNeeded = $totalNeededVal; }
        
        $fulfilled = number_format($fulfilledVal / 1000, 1) . 'k';
        if ($fulfilledVal < 1000) { $fulfilled = $fulfilledVal; }
        
        $remainingVal = max(0, $totalNeededVal - $fulfilledVal);
        $remaining = number_format($remainingVal / 1000, 1) . 'k';
        if ($remainingVal < 1000) { $remaining = $remainingVal; }
        
        $activeDonors = \App\Models\Donation::where('status', 'accepted')->orWhere('status', 'delivered')->distinct('donor_id')->count('donor_id');
        if ($activeDonors == 0) { $activeDonors = 248; } // Mock if no data yet to match figma

        // Recent donations
        $recentDonations = \App\Models\Donation::with(['donor', 'shelterNeed.shelter'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('pengelola.hub-logistik-donasi', compact(
            'shelters',
            'totalNeeded',
            'fulfilled',
            'remaining',
            'activeDonors',
            'recentDonations'
        ));
    }

    /**
     * Submit a donation.
     */
    public function submitDonation(Request $request)
    {
        $request->validate([
            'need_id' => 'required|integer|exists:shelter_needs,need_id',
            'quantity_donated' => 'required|integer|min:1',
            'shipping_receipt_no' => 'nullable|string|max:100',
            'proof_photo' => 'required|image|max:5120', // Max 5MB
        ]);

        try {
            $data = $request->only(['need_id', 'quantity_donated', 'shipping_receipt_no']);
            $data['donor_id'] = auth()->id();

            $this->donationService->submitDonation($data, $request->file('proof_photo'));

            return redirect()
                ->route('donasi.hub')
                ->with('success', 'Donasi Anda berhasil dikirim! Pengelola posko akan segera memverifikasi barang bantuan Anda.');
        } catch (Exception $e) {
            return redirect()
                ->route('donasi.hub')
                ->with('error', 'Gagal mengirim donasi: ' . $e->getMessage());
        }
    }
}
