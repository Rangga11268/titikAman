<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\FloodReport;
use App\Models\SosRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class WargaPortalTest extends TestCase
{
    use RefreshDatabase;

    private $wargaUser;

    protected function setUp(): void
    {
        parent::setUp();

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
     * Test guest cannot access citizen dashboard.
     */
    public function test_dashboard_requires_auth(): void
    {
        $response = $this->get(route('warga.dashboard'));
        $response->assertRedirect(route('login'));
    }

    /**
     * Test citizen can access dashboard.
     */
    public function test_warga_can_access_dashboard(): void
    {
        $response = $this->actingAs($this->wargaUser)->get(route('warga.dashboard'));
        $response->assertStatus(200);
        $response->assertViewIs('warga.dashboard');
    }

    /**
     * Test citizen can submit flood report.
     */
    public function test_warga_can_submit_flood_report(): void
    {
        Storage::fake('public');

        $photo = UploadedFile::fake()->image('flood.jpg');

        $response = $this->actingAs($this->wargaUser)->post(route('warga.lapor.submit'), [
            'water_height_cm' => 75,
            'street_name' => 'Jl. Kartini Raya',
            'latitude' => -6.2425,
            'longitude' => 107.0022,
            'photo_evidence' => $photo,
        ]);

        $response->assertRedirect(route('warga.dashboard'));
        $response->assertSessionHas('success');

        // Check report in database
        $this->assertDatabaseHas('flood_reports', [
            'user_id' => $this->wargaUser->user_id,
            'water_height_cm' => 75,
            'street_name' => 'Jl. Kartini Raya',
            'latitude' => -6.2425,
            'longitude' => 107.0022,
            'verification_status' => 'pending',
        ]);

        // Clean up any test upload files created in local storage
        $report = FloodReport::first();
        if ($report && $report->photo_evidence) {
            $fullPath = storage_path('app/public/' . $report->photo_evidence);
            if (file_exists($fullPath)) {
                @unlink($fullPath);
            }
        }
    }

    /**
     * Test citizen can submit SOS request.
     */
    public function test_warga_can_submit_sos_request_with_high_priority(): void
    {
        $response = $this->actingAs($this->wargaUser)->postJson(route('warga.sos.submit'), [
            'people_trapped' => 3,
            'vulnerable_groups_count' => 1, // Has vulnerable group -> High priority
            'description' => 'Ada balita demam dan butuh obat',
            'latitude' => -6.2349,
            'longitude' => 106.9994,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
        ]);

        $this->assertDatabaseHas('sos_requests', [
            'user_id' => $this->wargaUser->user_id,
            'people_trapped' => 3,
            'vulnerable_groups_count' => 1,
            'priority_level' => 'high',
            'status' => 'waiting',
        ]);
    }

    /**
     * Test citizen can submit SOS request with medium priority.
     */
    public function test_warga_can_submit_sos_request_with_medium_priority(): void
    {
        $response = $this->actingAs($this->wargaUser)->postJson(route('warga.sos.submit'), [
            'people_trapped' => 4,
            'vulnerable_groups_count' => 0, // No vulnerable groups, but people >= 3 -> Medium priority
            'description' => 'Terjebak di lantai 2',
            'latitude' => -6.2349,
            'longitude' => 106.9994,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('sos_requests', [
            'user_id' => $this->wargaUser->user_id,
            'people_trapped' => 4,
            'vulnerable_groups_count' => 0,
            'priority_level' => 'medium',
        ]);
    }
}
