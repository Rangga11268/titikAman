<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\SosRequest;
use App\Models\RescueMission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RelawanPortalTest extends TestCase
{
    use RefreshDatabase;

    private $volunteerUser;
    private $wargaUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Create volunteer
        $this->volunteerUser = User::create([
            'fullname' => 'Relawan Test',
            'email' => 'relawan@example.com',
            'phone' => '081299999999',
            'password' => bcrypt('password'),
            'role' => 'Relawan',
        ]);

        // Create citizen
        $this->wargaUser = User::create([
            'fullname' => 'Warga Test',
            'email' => 'warga@example.com',
            'phone' => '081288888888',
            'password' => bcrypt('password'),
            'role' => 'Warga',
        ]);
    }

    /**
     * Test guest cannot access volunteer dashboard.
     */
    public function test_volunteer_dashboard_requires_auth(): void
    {
        $response = $this->get(route('relawan.dashboard'));
        $response->assertRedirect(route('login'));
    }

    /**
     * Test user with wrong role cannot access volunteer dashboard.
     */
    public function test_citizen_cannot_access_volunteer_dashboard(): void
    {
        $response = $this->actingAs($this->wargaUser)->get(route('relawan.dashboard'));
        $response->assertStatus(403);
    }

    /**
     * Test volunteer can access dashboard.
     */
    public function test_volunteer_can_access_dashboard(): void
    {
        $response = $this->actingAs($this->volunteerUser)->get(route('relawan.dashboard'));
        $response->assertStatus(200);
        $response->assertViewIs('relawan.dashboard');
    }

    /**
     * Test volunteer can accept waiting SOS request.
     */
    public function test_volunteer_can_accept_waiting_sos(): void
    {
        // Create a waiting SOS
        $sos = SosRequest::create([
            'user_id' => $this->wargaUser->user_id,
            'latitude' => -6.2349,
            'longitude' => 106.9994,
            'people_trapped' => 2,
            'vulnerable_groups_count' => 0,
            'priority_level' => 'low',
            'status' => 'waiting',
        ]);

        $response = $this->actingAs($this->volunteerUser)->post(route('relawan.mission.accept'), [
            'sos_id' => $sos->sos_id,
        ]);

        $response->assertRedirect(route('relawan.dashboard'));
        $response->assertSessionHas('success');

        // Assert SOS status updated
        $this->assertDatabaseHas('sos_requests', [
            'sos_id' => $sos->sos_id,
            'status' => 'assigned',
        ]);

        // Assert rescue mission created
        $this->assertDatabaseHas('rescue_missions', [
            'sos_id' => $sos->sos_id,
            'volunteer_id' => $this->volunteerUser->user_id,
            'resolved_at' => null,
        ]);
    }

    /**
     * Test volunteer can complete active mission.
     */
    public function test_volunteer_can_complete_mission(): void
    {
        // Create SOS and claim it
        $sos = SosRequest::create([
            'user_id' => $this->wargaUser->user_id,
            'latitude' => -6.2349,
            'longitude' => 106.9994,
            'people_trapped' => 2,
            'vulnerable_groups_count' => 0,
            'priority_level' => 'low',
            'status' => 'assigned',
        ]);

        $mission = RescueMission::create([
            'sos_id' => $sos->sos_id,
            'volunteer_id' => $this->volunteerUser->user_id,
            'assigned_at' => now(),
        ]);

        $response = $this->actingAs($this->volunteerUser)->post(route('relawan.mission.complete', $mission->mission_id));

        $response->assertRedirect(route('relawan.dashboard'));
        $response->assertSessionHas('success');

        // Assert SOS marked completed
        $this->assertDatabaseHas('sos_requests', [
            'sos_id' => $sos->sos_id,
            'status' => 'completed',
        ]);

        // Assert mission resolved
        $mission->refresh();
        $this->assertNotNull($mission->resolved_at);
    }
}
