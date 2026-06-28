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
        $shelterId = null;
        if (auth()->check() && auth()->user()->role === 'Pengelola_Posko') {
            $shelterId = auth()->user()->shelter_id;
        }

        // Build base queries
        $sheltersQuery = Shelter::with(['shelterNeeds' => function ($query) {
            $query->whereColumn('quantity_fulfilled', '<', 'quantity_need')
                  ->orderBy('urgency', 'desc');
        }])->whereIn('status', ['active', 'full']);

        $needsQuery = \App\Models\ShelterNeed::query();
        $donationsQuery = \App\Models\Donation::query();
        
        if ($shelterId) {
            $sheltersQuery->where('shelter_id', $shelterId);
            $needsQuery->where('shelter_id', $shelterId);
            $donationsQuery->whereHas('shelterNeed', function($q) use ($shelterId) {
                $q->where('shelter_id', $shelterId);
            });
        }

        // Fetch shelters
        $shelters = $sheltersQuery->get();

        // Calculate dynamic stats
        $totalNeededObj = (clone $needsQuery)->selectRaw('SUM(quantity_need) as total_need, SUM(quantity_fulfilled) as total_fulfilled')->first();
        
        $totalNeededVal = (int) ($totalNeededObj->total_need ?? 0);
        $fulfilledVal   = (int) ($totalNeededObj->total_fulfilled ?? 0);
        
        $totalNeeded = $totalNeededVal >= 1000 ? number_format($totalNeededVal / 1000, 1) . 'k' : $totalNeededVal;
        $fulfilled   = $fulfilledVal   >= 1000 ? number_format($fulfilledVal   / 1000, 1) . 'k' : $fulfilledVal;
        
        $remainingVal = max(0, $totalNeededVal - $fulfilledVal);
        $remaining = $remainingVal >= 1000 ? number_format($remainingVal / 1000, 1) . 'k' : $remainingVal;

        // Fulfillment percentage for the mini progress bar
        $fulfillmentPercent = $totalNeededVal > 0 ? round(($fulfilledVal / $totalNeededVal) * 100) : 0;

        // Active donors – unique donors with at least one accepted/delivered donation
        $activeDonors = (clone $donationsQuery)->whereIn('status', ['accepted', 'delivered'])
            ->distinct('donor_id')
            ->count('donor_id');

        // Most active / first active shelter for the Right Column card
        $topShelter = (clone $sheltersQuery)->orderByDesc('current_occupants')->first();

        // Recent donations with status filter support (all by default, filter via JS)
        $recentDonations = (clone $donationsQuery)->with(['donor', 'shelterNeed.shelter'])
            ->latest()
            ->take(10)
            ->get();

        // Donation status counts for filter badges
        $donationStats = [
            'pending'   => (clone $donationsQuery)->where('status', 'pending')->count(),
            'accepted'  => (clone $donationsQuery)->whereIn('status', ['accepted', 'delivered'])->count(),
            'rejected'  => (clone $donationsQuery)->where('status', 'rejected')->count(),
        ];

        return view('pengelola.hub-logistik-donasi', compact(
            'shelters',
            'totalNeeded',
            'fulfilled',
            'remaining',
            'activeDonors',
            'fulfillmentPercent',
            'recentDonations',
            'topShelter',
            'donationStats'
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
                ->back(fallback: route('donasi.hub'))
                ->with('success', 'Donasi Anda berhasil dikirim! Pengelola posko akan segera memverifikasi barang bantuan Anda.');
        } catch (Exception $e) {
            return redirect()
                ->back(fallback: route('donasi.hub'))
                ->with('error', 'Gagal mengirim donasi: ' . $e->getMessage());
        }
    }

    /**
     * Export all donations as CSV.
     */
    public function exportDonations()
    {
        $donations = \App\Models\Donation::with(['donor', 'shelterNeed.shelter'])
            ->orderBy('created_at', 'desc')
            ->get();

        $fileName = 'donations_' . time() . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID Donasi', 'Donatur', 'Posko Tujuan', 'Barang Bantuan', 'Jumlah', 'No Resi Pengiriman', 'Status', 'Tanggal'];

        $callback = function() use($donations, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($donations as $donation) {
                fputcsv($file, [
                    $donation->donation_id,
                    $donation->donor ? $donation->donor->fullname : 'Anonim',
                    ($donation->shelterNeed && $donation->shelterNeed->shelter) ? $donation->shelterNeed->shelter->shelter_name : '-',
                    $donation->shelterNeed ? $donation->shelterNeed->item_name : '-',
                    $donation->quantity_donated,
                    $donation->shipping_receipt_no ?? '-',
                    $donation->status,
                    $donation->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
