<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SharedAccessTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;
    protected $relawanUser;
    protected $pengelolaUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::create([
            'fullname' => 'Admin BPBD',
            'email' => 'admin@example.com',
            'phone' => '081200000001',
            'password' => bcrypt('password'),
            'role' => 'Admin_BPBD',
        ]);

        $this->relawanUser = User::create([
            'fullname' => 'Admin Relawan',
            'email' => 'relawan@example.com',
            'phone' => '081299999999',
            'password' => bcrypt('password'),
            'role' => 'Admin_Relawan',
        ]);

        $this->pengelolaUser = User::create([
            'fullname' => 'Pengelola Test',
            'email' => 'pengelola@example.com',
            'phone' => '081200000003',
            'password' => bcrypt('password'),
            'role' => 'Pengelola_Posko',
        ]);
    }

    /**
     * Test admin can access shared pages.
     */
    public function test_admin_can_access_shared_pages(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('peta.evakuasi'));
        $response->assertStatus(200);

        $response = $this->actingAs($this->adminUser)->get(route('pintu.air'));
        $response->assertStatus(200);

        $response = $this->actingAs($this->adminUser)->get(route('posko'));
        $response->assertStatus(200);
    }

    /**
     * Test relawan can access shared pages.
     */
    public function test_relawan_can_access_shared_pages(): void
    {
        $response = $this->actingAs($this->relawanUser)->get(route('peta.evakuasi'));
        $response->assertStatus(200);

        $response = $this->actingAs($this->relawanUser)->get(route('pintu.air'));
        $response->assertStatus(200);

        $response = $this->actingAs($this->relawanUser)->get(route('posko'));
        $response->assertStatus(200);
    }

    /**
     * Test pengelola can access shared pages.
     */
    public function test_pengelola_can_access_shared_pages(): void
    {
        $response = $this->actingAs($this->pengelolaUser)->get(route('peta.evakuasi'));
        $response->assertStatus(200);

        $response = $this->actingAs($this->pengelolaUser)->get(route('pintu.air'));
        $response->assertStatus(200);

        $response = $this->actingAs($this->pengelolaUser)->get(route('posko'));
        $response->assertStatus(200);
    }
}
