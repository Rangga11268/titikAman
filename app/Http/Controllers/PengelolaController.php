<?php

namespace App\Http\Controllers;

use App\Repositories\ShelterRepository;
use App\Repositories\ShelterNeedRepository;
use App\Repositories\DonationRepository;
use App\Services\DonationService;
use App\Models\Shelter;
use Illuminate\Http\Request;
use Exception;

class PengelolaController extends Controller
{
    protected $shelterRepository;
    protected $shelterNeedRepository;
    protected $donationRepository;
    protected $donationService;

    public function __construct(
        ShelterRepository $shelterRepository,
        ShelterNeedRepository $shelterNeedRepository,
        DonationRepository $donationRepository,
        DonationService $donationService
    ) {
        $this->shelterRepository = $shelterRepository;
        $this->shelterNeedRepository = $shelterNeedRepository;
        $this->donationRepository = $donationRepository;
        $this->donationService = $donationService;
    }

    /**
     * Get the currently managed shelter ID based on role.
     */
    private function getManagedShelterId()
    {
        if (auth()->user()->role === 'Admin_BPBD') {
            return session('managed_shelter_id');
        }
        return auth()->user()->shelter_id;
    }

    /**
     * Show the shelter manager dashboard.
     */
    public function dashboard(Request $request)
    {
        if ($request->query('clear_shelter') == 1 && auth()->user()->role === 'Admin_BPBD') {
            session()->forget('managed_shelter_id');
            return redirect()->route('pengelola.dashboard');
        }

        $shelterId = $this->getManagedShelterId();
        
        if (!$shelterId) {
            if (auth()->user()->role === 'Admin_BPBD') {
                // Get all shelters to let Admin choose
                $shelters = Shelter::all();
                return view('pengelola.kelola-kebutuhan', compact('shelters'));
            }
            return redirect()->route('dashboard')->with('error', 'Anda tidak terhubung dengan posko manapun.');
        }

        $shelter = Shelter::find($shelterId);
        if (!$shelter) {
            if (auth()->user()->role === 'Admin_BPBD') {
                session()->forget('managed_shelter_id');
            }
            return redirect()->route('dashboard')->with('error', 'Posko tidak ditemukan.');
        }

        $needs = $this->shelterNeedRepository->getNeedsByShelterId($shelterId);
        $donations = $this->donationRepository->getDonationsByShelterId($shelterId);
        return view('pengelola.kelola-kebutuhan', compact('shelter', 'needs', 'donations'));
    }

    /**
     * Set the managed shelter in session (Admin only).
     */
    public function selectShelter(Request $request)
    {
        if (auth()->user()->role !== 'Admin_BPBD') {
            abort(403, 'Hanya Admin yang dapat mengubah posko.');
        }

        $request->validate([
            'shelter_id' => 'required|exists:shelters,shelter_id',
        ]);

        session(['managed_shelter_id' => $request->shelter_id]);

        return redirect()
            ->route('pengelola.dashboard')
            ->with('success', 'Berhasil memilih posko untuk dikelola.');
    }

    /**
     * Update shelter capacity and status.
     */
    public function updateShelter(Request $request)
    {
        $shelterId = $this->getManagedShelterId();
        if (!$shelterId) {
            return redirect()->route('pengelola.dashboard')->with('error', 'Silakan pilih posko terlebih dahulu.');
        }

        $request->validate([
            'current_occupants' => 'required|integer|min:0',
            'has_toilet_facilities' => 'required|in:Yes,No',
            'status' => 'required|in:active,full,closed',
        ]);

        try {
            $shelter = Shelter::findOrFail($shelterId);
            $shelter->update($request->only(['current_occupants', 'has_toilet_facilities', 'status']));

            return redirect()
                ->route('pengelola.dashboard')
                ->with('success', 'Detail posko berhasil diperbarui.');
        } catch (Exception $e) {
            return redirect()
                ->route('pengelola.dashboard')
                ->with('error', 'Gagal memperbarui posko: ' . $e->getMessage());
        }
    }

    /**
     * Add a new shelter need item.
     */
    public function addNeed(Request $request)
    {
        $shelterId = $this->getManagedShelterId();
        if (!$shelterId) {
            return redirect()->route('pengelola.dashboard')->with('error', 'Silakan pilih posko terlebih dahulu.');
        }

        $request->validate([
            'item_name' => 'required|string|max:100',
            'quantity_need' => 'required|integer|min:1',
            'urgency' => 'required|in:low,medium,high',
        ]);

        try {
            $data = $request->only(['item_name', 'quantity_need', 'urgency']);
            $data['shelter_id'] = $shelterId;
            $data['quantity_fulfilled'] = 0; // Default is 0

            $this->shelterNeedRepository->create($data);

            return redirect()
                ->route('pengelola.dashboard')
                ->with('success', 'Kebutuhan logistik baru berhasil ditambahkan.');
        } catch (Exception $e) {
            return redirect()
                ->route('pengelola.dashboard')
                ->with('error', 'Gagal menambahkan kebutuhan: ' . $e->getMessage());
        }
    }

    /**
     * Accept/Verify an incoming donation.
     */
    public function updateDonationStatus(Request $request, int $donationId)
    {
        $shelterId = $this->getManagedShelterId();
        if (!$shelterId) {
            return redirect()->route('pengelola.dashboard')->with('error', 'Silakan pilih posko terlebih dahulu.');
        }

        $request->validate([
            'status' => 'required|in:accepted,delivered',
        ]);

        try {
            $this->donationService->verifyDonation($donationId, $request->status);

            $message = $request->status === 'delivered' 
                ? 'Donasi berhasil diterima dan dicatat ke logistik posko.' 
                : 'Donasi disetujui.';

            return redirect()
                ->route('pengelola.dashboard')
                ->with('success', $message);
        } catch (Exception $e) {
            return redirect()
                ->route('pengelola.dashboard')
                ->with('error', 'Gagal memverifikasi donasi: ' . $e->getMessage());
        }
    }
}
