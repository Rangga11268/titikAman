<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Shelter;
use App\Models\ShelterNeed;
use App\Models\Donation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LogisticsDonationTest extends TestCase
{
    use RefreshDatabase;

    private $managerUser;
    private $wargaUser;
    private $shelter;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a Shelter Manager User
        $this->managerUser = User::create([
            'fullname' => 'Pengelola Test',
            'email' => 'pengelola@example.com',
            'phone' => '081211111111',
            'password' => bcrypt('password'),
            'role' => 'Pengelola_Posko',
        ]);

        // Create a Citizen User (Donor)
        $this->wargaUser = User::create([
            'fullname' => 'Warga Test',
            'email' => 'warga@example.com',
            'phone' => '081222222222',
            'password' => bcrypt('password'),
            'role' => 'Warga',
        ]);

        // Create an Initial Shelter
        $this->shelter = Shelter::create([
            'shelter_name' => 'Posko Kantor Desa Margahayu',
            'address' => 'Jl. Kartini No. 12, Bekasi Timur',
            'max_capacity' => 100,
            'current_occupants' => 30,
            'has_toilet_facilities' => 'Yes',
            'status' => 'active',
            'latitude' => -6.2425,
            'longitude' => 107.0022,
        ]);
    }

    /**
     * Test guest cannot access shelter manager dashboard.
     */
    public function test_pengelola_dashboard_requires_auth(): void
    {
        $response = $this->get(route('pengelola.dashboard'));
        $response->assertRedirect(route('login'));
    }

    /**
     * Test user with wrong role cannot access shelter manager dashboard.
     */
    public function test_wrong_role_cannot_access_pengelola_dashboard(): void
    {
        $response = $this->actingAs($this->wargaUser)->get(route('pengelola.dashboard'));
        $response->assertStatus(403);
    }

    /**
     * Test shelter manager can access dashboard, select shelter, and see shelter view.
     */
    public function test_pengelola_can_access_dashboard_and_select_shelter(): void
    {
        // 1. First dashboard access (no shelter selected yet)
        $response = $this->actingAs($this->managerUser)->get(route('pengelola.dashboard'));
        $response->assertStatus(200);
        $response->assertViewIs('pengelola.kelola-kebutuhan');
        $response->assertViewHas('shelters');
        $response->assertSee($this->shelter->shelter_name);

        // 2. Select shelter (Post to select-shelter)
        $response = $this->actingAs($this->managerUser)->post(route('pengelola.select-shelter'), [
            'shelter_id' => $this->shelter->shelter_id,
        ]);
        $response->assertRedirect(route('pengelola.dashboard'));
        $response->assertSessionHas('success');
        $this->assertEquals($this->shelter->shelter_id, session('managed_shelter_id'));

        // 3. Second dashboard access (shelter selected)
        $response = $this->actingAs($this->managerUser)->get(route('pengelola.dashboard'));
        $response->assertStatus(200);
        $response->assertViewIs('pengelola.kelola-kebutuhan');
        $response->assertViewHas('shelter');
        $response->assertViewHas('needs');
        $response->assertViewHas('donations');
        $response->assertSee($this->shelter->shelter_name);
    }

    /**
     * Test shelter manager can update shelter details.
     */
    public function test_pengelola_can_update_shelter_details(): void
    {
        // Set the managed shelter in session
        $this->actingAs($this->managerUser)->withSession([
            'managed_shelter_id' => $this->shelter->shelter_id,
        ]);

        $response = $this->actingAs($this->managerUser)->post(route('pengelola.shelter.update'), [
            'current_occupants' => 45,
            'status' => 'full',
            'has_toilet_facilities' => 'No',
        ]);

        $response->assertRedirect(route('pengelola.dashboard'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('shelters', [
            'shelter_id' => $this->shelter->shelter_id,
            'current_occupants' => 45,
            'status' => 'full',
            'has_toilet_facilities' => 'No',
        ]);
    }

    /**
     * Test shelter manager can add logistical need.
     */
    public function test_pengelola_can_add_shelter_need(): void
    {
        // Set the managed shelter in session
        $this->actingAs($this->managerUser)->withSession([
            'managed_shelter_id' => $this->shelter->shelter_id,
        ]);

        $response = $this->actingAs($this->managerUser)->post(route('pengelola.need.add'), [
            'item_name' => 'Selimut Dewasa',
            'quantity_need' => 50,
            'urgency' => 'high',
        ]);

        $response->assertRedirect(route('pengelola.dashboard'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('shelter_needs', [
            'shelter_id' => $this->shelter->shelter_id,
            'item_name' => 'Selimut Dewasa',
            'quantity_need' => 50,
            'quantity_fulfilled' => 0,
            'urgency' => 'high',
        ]);
    }

    /**
     * Test user (all roles) can access donation hub.
     */
    public function test_user_can_view_donasi_hub(): void
    {
        // Create an unfulfilled need for the shelter
        $need = ShelterNeed::create([
            'shelter_id' => $this->shelter->shelter_id,
            'item_name' => 'Air Mineral Dus',
            'quantity_need' => 20,
            'quantity_fulfilled' => 5,
            'urgency' => 'medium',
        ]);

        $response = $this->actingAs($this->managerUser)->get(route('donasi.hub'));
        $response->assertStatus(200);
        $response->assertViewIs('pengelola.hub-logistik-donasi');
        $response->assertViewHas('shelters');
        $response->assertSee('Air Mineral Dus');
    }

    /**
     * Test user can submit a donation request.
     */
    public function test_user_can_submit_donation(): void
    {
        Storage::fake('public');

        // Create an unfulfilled need
        $need = ShelterNeed::create([
            'shelter_id' => $this->shelter->shelter_id,
            'item_name' => 'Air Mineral Dus',
            'quantity_need' => 20,
            'quantity_fulfilled' => 5,
            'urgency' => 'medium',
        ]);

        $photo = UploadedFile::fake()->image('donation_proof.jpg');

        $response = $this->actingAs($this->managerUser)->post(route('donasi.submit'), [
            'need_id' => $need->need_id,
            'quantity_donated' => 10,
            'shipping_receipt_no' => 'TA-DONATE-999',
            'proof_photo' => $photo,
        ]);

        $response->assertRedirect(route('donasi.hub'));
        $response->assertSessionHas('success');

        // Verify in database
        $this->assertDatabaseHas('donations', [
            'donor_id' => $this->managerUser->user_id,
            'need_id' => $need->need_id,
            'quantity_donated' => 10,
            'shipping_receipt_no' => 'TA-DONATE-999',
            'status' => 'pending',
        ]);

        // Clean up uploaded files in local storage if any
        $donation = Donation::first();
        if ($donation && $donation->proof_photo) {
            $fullPath = storage_path('app/public/' . $donation->proof_photo);
            if (file_exists($fullPath)) {
                @unlink($fullPath);
            }
        }
    }

    /**
     * Test shelter manager can verify donation and auto-update unfulfilled quantity.
     */
    public function test_pengelola_can_verify_donation(): void
    {
        // 1. Create need
        $need = ShelterNeed::create([
            'shelter_id' => $this->shelter->shelter_id,
            'item_name' => 'Air Mineral Dus',
            'quantity_need' => 20,
            'quantity_fulfilled' => 5,
            'urgency' => 'medium',
        ]);

        // 2. Create pending donation
        $donation = Donation::create([
            'donor_id' => $this->wargaUser->user_id,
            'need_id' => $need->need_id,
            'quantity_donated' => 10,
            'shipping_receipt_no' => 'TA-DONATE-999',
            'proof_photo' => 'donations/fake.jpg',
            'status' => 'pending',
            'donated_at' => now(),
        ]);

        // Set the managed shelter in session
        $this->actingAs($this->managerUser)->withSession([
            'managed_shelter_id' => $this->shelter->shelter_id,
        ]);

        // Verify as delivered (received)
        $response = $this->actingAs($this->managerUser)->post(route('pengelola.donation.verify', $donation->donation_id), [
            'status' => 'delivered',
        ]);

        $response->assertRedirect(route('pengelola.dashboard'));
        $response->assertSessionHas('success');

        // Check if donation status updated
        $this->assertDatabaseHas('donations', [
            'donation_id' => $donation->donation_id,
            'status' => 'delivered',
        ]);

        // Check if shelter need quantity_fulfilled is updated (5 + 10 = 15)
        $this->assertDatabaseHas('shelter_needs', [
            'need_id' => $need->need_id,
            'quantity_fulfilled' => 15,
        ]);
    }
}
