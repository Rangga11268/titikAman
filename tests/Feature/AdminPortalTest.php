<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\FloodReport;
use App\Models\WaterGate;
use App\Events\TmaThresholdExceeded;
use App\Jobs\SendEarlyWarningNotificationJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class AdminPortalTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $wargaUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::create([
            'fullname' => 'Admin BPBD',
            'email' => 'admin@example.com',
            'phone' => '081277777777',
            'password' => bcrypt('password'),
            'role' => 'Admin_BPBD',
        ]);

        $this->wargaUser = User::create([
            'fullname' => 'Warga Test',
            'email' => 'warga@example.com',
            'phone' => '081234567890',
            'password' => bcrypt('password'),
            'role' => 'Warga',
            'kecamatan' => 'Bekasi Timur',
            'kelurahan' => 'Margahayu',
        ]);
    }

    /**
     * Test wrong role cannot access admin routes.
     */
    public function test_wrong_role_cannot_access_admin_dashboard(): void
    {
        $response = $this->actingAs($this->wargaUser)->get(route('admin.dashboard'));
        $response->assertStatus(403);
    }

    /**
     * Test admin can access dashboard.
     */
    public function test_admin_can_access_dashboard(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');
    }

    /**
     * Test admin can verify a pending flood report.
     */
    public function test_admin_can_verify_flood_report(): void
    {
        $report = FloodReport::create([
            'user_id' => $this->wargaUser->user_id,
            'water_height_cm' => 120,
            'street_name' => 'Jl. Mawar',
            'latitude' => -6.24,
            'longitude' => 107.0,
            'verification_status' => 'pending',
        ]);

        $response = $this->actingAs($this->adminUser)->post(route('admin.report.verify', $report->report_id));

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertDatabaseHas('flood_reports', [
            'report_id' => $report->report_id,
            'verification_status' => 'verified',
        ]);
    }

    /**
     * Test admin can reject a pending flood report.
     */
    public function test_admin_can_reject_flood_report(): void
    {
        $report = FloodReport::create([
            'user_id' => $this->wargaUser->user_id,
            'water_height_cm' => 120,
            'street_name' => 'Jl. Melati',
            'latitude' => -6.24,
            'longitude' => 107.0,
            'verification_status' => 'pending',
        ]);

        $response = $this->actingAs($this->adminUser)->post(route('admin.report.reject', $report->report_id));

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertDatabaseHas('flood_reports', [
            'report_id' => $report->report_id,
            'verification_status' => 'rejected',
        ]);
    }

    /**
     * Test admin can view tma page.
     */
    public function test_admin_can_view_tma_page(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.tma'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.tma');
    }

    /**
     * Test admin can update TMA level.
     */
    public function test_admin_can_update_tma_level(): void
    {
        $gate = WaterGate::create([
            'gate_name' => 'Pintu Air A',
            'river_name' => 'Citarum',
            'water_level_cm' => 50,
            'danger_status' => 'Normal',
        ]);

        $response = $this->actingAs($this->adminUser)->post(route('admin.tma.update', $gate->gate_id), [
            'water_level_cm' => 160, // Siaga_2
        ]);

        $response->assertRedirect(route('admin.tma'));
        $this->assertDatabaseHas('water_gates', [
            'gate_id' => $gate->gate_id,
            'water_level_cm' => 160,
            'danger_status' => 'Siaga_2',
        ]);
    }

    /**
     * Test threshold triggers event and dispatches early warning.
     */
    public function test_tma_threshold_triggers_event_and_warning_job(): void
    {
        Event::fake();
        Queue::fake();

        $gate = WaterGate::create([
            'gate_name' => 'Pintu Air B',
            'river_name' => 'Ciliwung',
            'water_level_cm' => 50,
            'danger_status' => 'Normal',
        ]);

        $response = $this->actingAs($this->adminUser)->post(route('admin.tma.update', $gate->gate_id), [
            'water_level_cm' => 260, // Siaga_1
        ]);

        Event::assertDispatched(TmaThresholdExceeded::class);
    }

    /**
     * Test admin can export reports.
     */
    public function test_admin_can_export_reports(): void
    {
        FloodReport::create([
            'user_id' => $this->wargaUser->user_id,
            'water_height_cm' => 80,
            'street_name' => 'Jl. Mawar',
            'latitude' => -6.24,
            'longitude' => 107.0,
            'verification_status' => 'verified',
        ]);

        $response = $this->actingAs($this->adminUser)->get(route('admin.report.export'));
        
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
    }
}
