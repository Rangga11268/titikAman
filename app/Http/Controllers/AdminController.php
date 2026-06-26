<?php

namespace App\Http\Controllers;

use App\Services\AdminService;
use App\Models\FloodReport;
use App\Models\WaterGate;
use App\Jobs\ExportReportsJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    protected $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }

    /**
     * Moderation Dashboard for BPBD Admin
     * Figma Node 163:2189 (3-column layout)
     */
    public function dashboard(Request $request)
    {
        $pendingReports = FloodReport::with("user")
            ->where("verification_status", "pending")
            ->orderBy("created_at", "asc")
            ->get();

        $selectedReportId = $request->query("report_id");
        $selectedReport = null;

        if ($selectedReportId) {
            $selectedReport = FloodReport::with("user")->find(
                $selectedReportId,
            );
        }

        if (!$selectedReport && $pendingReports->isNotEmpty()) {
            $selectedReport = $pendingReports->first();
        }

        // Today's moderation log (verified/rejected today)
        $moderatedToday = FloodReport::with("user")
            ->whereIn("verification_status", ["verified", "rejected"])
            ->whereDate("updated_at", today())
            ->orderBy("updated_at", "desc")
            ->get();

        // All verified reports for summary map
        $verifiedReports = FloodReport::where(
            "verification_status",
            "verified",
        )->get();

        return view(
            "admin.dashboard",
            compact(
                "pendingReports",
                "selectedReport",
                "moderatedToday",
                "verifiedReports",
            ),
        );
    }

    /**
     * Verify flood report
     */
    public function verifyReport($id)
    {
        $this->adminService->verifyReport($id);

        return redirect()
            ->route("admin.dashboard")
            ->with("success", "Laporan berhasil diverifikasi!");
    }

    /**
     * Reject flood report
     */
    public function rejectReport($id)
    {
        $this->adminService->rejectReport($id);

        return redirect()
            ->route("admin.dashboard")
            ->with("success", "Laporan berhasil ditolak!");
    }

    /**
     * Water Gate Level Management Dashboard
     */
    public function tma()
    {
        WaterGate::syncAllDangerStatuses();

        $waterGates = WaterGate::orderBy("danger_status", "desc")->get();

        return view("admin.tma", compact("waterGates"));
    }

    /**
     * Update TMA value
     */
    public function updateTma(Request $request, $id)
    {
        $request->validate([
            "water_level_cm" => "required|numeric|min:0",
        ]);

        $gate = $this->adminService->updateTma($id, $request->water_level_cm);

        return redirect()
            ->route("admin.tma")
            ->with(
                "success",
                "Tinggi Muka Air Pintu Air {$gate->gate_name} berhasil diperbarui menjadi {$gate->water_level_cm} cm (Status: {$gate->danger_status}).",
            );
    }

    /**
     * Export Flood Reports to CSV using asynchronous job
     */
    public function exportReports()
    {
        $filePath = "reports/flood_reports_" . time() . ".csv";

        // Execute sync or dispatch depending on queue setup
        ExportReportsJob::dispatchSync($filePath);

        if (Storage::disk("local")->exists($filePath)) {
            return Storage::disk("local")->download($filePath);
        }

        return redirect()->back()->with("error", "Gagal mengekspor laporan.");
    }

    /**
     * User Verifications Page
     */
    public function userVerification(Request $request)
    {
        $pendingUsers = $this->adminService->getPendingUsers();

        $selectedUserId = $request->query("user_id");
        $selectedUser = null;

        if ($selectedUserId) {
            $selectedUser = \App\Models\User::find($selectedUserId);
        }

        if (!$selectedUser && $pendingUsers->isNotEmpty()) {
            $selectedUser = $pendingUsers->first();
        }

        return view(
            "admin.verifikasi-pengguna",
            compact("pendingUsers", "selectedUser"),
        );
    }

    /**
     * Approve user
     */
    public function approveUser($id)
    {
        $this->adminService->approveUser($id);

        return redirect()
            ->route("admin.user-verification")
            ->with("success", "Akun pengguna berhasil disetujui!");
    }

    /**
     * Reject user
     */
    public function rejectUser($id)
    {
        $this->adminService->rejectUser($id);

        return redirect()
            ->route("admin.user-verification")
            ->with("success", "Pendaftaran akun pengguna telah ditolak.");
    }
}
