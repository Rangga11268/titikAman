<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WaterGate;
use App\Models\FloodReport;
use App\Models\Donation;
use App\Models\Shelter;
use App\Models\ShelterNeed;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportExportTest extends TestCase
{
    use RefreshDatabase;

    private $wargaUser;
    private $pengelolaUser;

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

        $this->pengelolaUser = User::create([
            'fullname' => 'Pengelola Test',
            'email' => 'pengelola@example.com',
            'phone' => '081211111111',
            'password' => bcrypt('password'),
            'role' => 'Pengelola_Posko',
            'kecamatan' => 'Bekasi Timur',
            'kelurahan' => 'Margahayu',
        ]);
    }

    /**
     * Test watergate export returns a streamed CSV response.
     */
    public function test_watergate_export(): void
    {
        // Seed a gate
        WaterGate::create([
            'gate_name' => 'Pintu Air Test',
            'river_name' => 'Sungai Test',
            'water_level_cm' => 120.00,
            'danger_status' => 'Normal',
            'last_updated' => now(),
        ]);

        $response = $this->actingAs($this->wargaUser)->get(route('watergate.export'));

        $response->assertStatus(200);
        $response->assertHeader('Content-type', 'text/csv; charset=utf-8');
        $this->assertStringContainsString('attachment; filename=tma_recap_', $response->headers->get('Content-Disposition'));
        $this->assertStringContainsString('Nama Pintu Air', $response->streamedContent());
        $this->assertStringContainsString('Nama Sungai', $response->streamedContent());
        $this->assertStringContainsString('Pintu Air Test', $response->streamedContent());
        $this->assertStringContainsString('Sungai Test', $response->streamedContent());
    }

    /**
     * Test flood reports export returns a streamed CSV response.
     */
    public function test_laporan_export(): void
    {
        // Seed a verified report
        FloodReport::create([
            'user_id' => $this->wargaUser->user_id,
            'water_height_cm' => 80,
            'street_name' => 'Jl. Test Raya',
            'latitude' => -6.2425,
            'longitude' => 107.0022,
            'verification_status' => 'verified',
        ]);

        $response = $this->actingAs($this->wargaUser)->get(route('laporan.export'));

        $response->assertStatus(200);
        $response->assertHeader('Content-type', 'text/csv; charset=utf-8');
        $this->assertStringContainsString('ID Laporan', $response->streamedContent());
        $this->assertStringContainsString('Pelapor', $response->streamedContent());
        $this->assertStringContainsString('Tinggi Air (cm)', $response->streamedContent());
        $this->assertStringContainsString('Jl. Test Raya', $response->streamedContent());
        $this->assertStringContainsString('verified', $response->streamedContent());
    }

    /**
     * Test donations export returns a streamed CSV response for pengelola.
     */
    public function test_donations_export(): void
    {
        // Seed shelter and need
        $shelter = Shelter::create([
            'shelter_name' => 'Posko Test',
            'address' => 'Jl. Posko Test',
            'max_capacity' => 100,
            'current_occupants' => 10,
            'latitude' => -6.2349,
            'longitude' => 106.9994,
        ]);

        $need = ShelterNeed::create([
            'shelter_id' => $shelter->shelter_id,
            'item_name' => 'Biskuit',
            'quantity_need' => 100,
            'quantity_fulfilled' => 10,
            'urgency' => 'medium',
        ]);

        // Seed a donation
        Donation::create([
            'donor_id' => $this->wargaUser->user_id,
            'need_id' => $need->need_id,
            'quantity_donated' => 20,
            'shipping_receipt_no' => 'RESI-123',
            'status' => 'accepted',
            'proof_photo' => 'test.jpg',
        ]);

        $response = $this->actingAs($this->pengelolaUser)->get(route('donasi.export'));

        $response->assertStatus(200);
        $response->assertHeader('Content-type', 'text/csv; charset=utf-8');
        $this->assertStringContainsString('ID Donasi', $response->streamedContent());
        $this->assertStringContainsString('Donatur', $response->streamedContent());
        $this->assertStringContainsString('Posko Tujuan', $response->streamedContent());
        $this->assertStringContainsString('Posko Test', $response->streamedContent());
        $this->assertStringContainsString('Biskuit', $response->streamedContent());
        $this->assertStringContainsString('20', $response->streamedContent());
    }
}
