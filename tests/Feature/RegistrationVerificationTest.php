<?php

namespace Tests\Feature;

use App\Models\Shelter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RegistrationVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->adminUser = User::create([
            'fullname' => 'Admin BPBD',
            'email' => 'admin@example.com',
            'phone' => '081277777777',
            'password' => Hash::make('password'),
            'role' => 'Admin_BPBD',
            'status' => 'approved',
        ]);
    }

    public function test_registration_pages_can_be_rendered(): void
    {
        $this->get(route('register.step2.relawan'))->assertOk();
        $this->get(route('register.step2.pengelola'))->assertOk();
    }

    public function test_relawan_can_register_with_pending_status(): void
    {
        $document = UploadedFile::fake()->create('ktp.pdf', 100, 'application/pdf');

        $response = $this->post(route('register.step2.relawan.submit'), [
            'fullname' => 'Relawan Test',
            'email' => 'relawan@example.com',
            'phone' => '081111111111',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'nik' => '3201234567890001',
            'keahlian' => ['Medis', 'Evakuasi'],
            'organisasi' => 'PMI Bekasi',
            'document' => $document,
        ]);

        $response->assertRedirect(route('register.success.relawan'));
        $response->assertSessionHas('success');
        $this->assertGuest();

        $this->assertDatabaseHas('users', [
            'email' => 'relawan@example.com',
            'role' => 'Relawan',
            'nik' => '3201234567890001',
            'keahlian' => 'Medis, Evakuasi',
            'organisasi' => 'PMI Bekasi',
            'status' => 'pending',
        ]);

        $user = User::where('email', 'relawan@example.com')->first();
        $this->assertNotNull($user->document_path);
        Storage::disk('public')->assertExists($user->document_path);
    }

    public function test_pengelola_posko_can_register_with_shelter(): void
    {
        $response = $this->post(route('register.step2.pengelola.submit'), [
            'fullname' => 'Pengelola Posko',
            'email' => 'pengelola@example.com',
            'phone' => '083333333333',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'shelter_name' => 'Posko Jatiasih',
            'max_capacity' => 200,
            'address' => 'Jl. Raya Jatiasih No. 1',
            'facilities' => ['Dapur Umum', 'Toilet'],
            'latitude' => -6.2891,
            'longitude' => 107.0021,
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success');
        $this->assertGuest();

        $this->assertDatabaseHas('shelters', [
            'shelter_name' => 'Posko Jatiasih',
            'max_capacity' => 200,
            'has_toilet_facilities' => 'Yes',
            'status' => 'active',
        ]);

        $shelter = Shelter::where('shelter_name', 'Posko Jatiasih')->first();

        $this->assertDatabaseHas('users', [
            'email' => 'pengelola@example.com',
            'role' => 'Pengelola_Posko',
            'shelter_id' => $shelter->shelter_id,
            'status' => 'pending',
        ]);

        $this->assertEquals(['Dapur Umum', 'Toilet'], $shelter->facilities);
    }

    public function test_pending_user_cannot_login(): void
    {
        User::create([
            'fullname' => 'Relawan Pending',
            'email' => 'pending@example.com',
            'phone' => '084444444444',
            'password' => Hash::make('password123'),
            'role' => 'Relawan',
            'nik' => '3201234567890002',
            'status' => 'pending',
        ]);

        $response = $this->post('/login', [
            'login_id' => 'pending@example.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('login_id');
        $this->assertGuest();
    }

    public function test_rejected_user_cannot_login(): void
    {
        User::create([
            'fullname' => 'Relawan Rejected',
            'email' => 'rejected@example.com',
            'phone' => '085555555555',
            'password' => Hash::make('password123'),
            'role' => 'Relawan',
            'nik' => '3201234567890003',
            'status' => 'rejected',
        ]);

        $response = $this->post('/login', [
            'login_id' => 'rejected@example.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('login_id');
        $this->assertGuest();
    }

    public function test_approved_user_can_login_after_verification(): void
    {
        User::create([
            'fullname' => 'Relawan Approved',
            'email' => 'approved@example.com',
            'phone' => '086666666666',
            'password' => Hash::make('password123'),
            'role' => 'Relawan',
            'nik' => '3201234567890004',
            'status' => 'approved',
        ]);

        $response = $this->post('/login', [
            'login_id' => 'approved@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    public function test_warga_cannot_access_user_verification_page(): void
    {
        $warga = User::create([
            'fullname' => 'Warga Test',
            'email' => 'warga@example.com',
            'phone' => '081234567890',
            'password' => Hash::make('password'),
            'role' => 'Warga',
            'kecamatan' => 'Bekasi Timur',
            'kelurahan' => 'Margahayu',
            'status' => 'approved',
        ]);

        $response = $this->actingAs($warga)->get(route('admin.user-verification'));

        $response->assertStatus(403);
    }

    public function test_admin_can_view_user_verification_page(): void
    {
        User::create([
            'fullname' => 'Relawan Pending',
            'email' => 'verify@example.com',
            'phone' => '087777777777',
            'password' => Hash::make('password123'),
            'role' => 'Relawan',
            'nik' => '3201234567890005',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->adminUser)->get(route('admin.user-verification'));

        $response->assertOk();
        $response->assertViewIs('admin.verifikasi-pengguna');
        $response->assertSee('Relawan Pending');
    }

    public function test_admin_can_approve_pending_relawan(): void
    {
        $relawan = User::create([
            'fullname' => 'Relawan Approve',
            'email' => 'approve@example.com',
            'phone' => '088888888888',
            'password' => Hash::make('password123'),
            'role' => 'Relawan',
            'nik' => '3201234567890006',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->adminUser)->post(
            route('admin.user.approve', $relawan->user_id)
        );

        $response->assertRedirect(route('admin.user-verification'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'user_id' => $relawan->user_id,
            'status' => 'approved',
        ]);
    }

    public function test_admin_can_reject_pending_pengelola_posko(): void
    {
        $shelter = Shelter::create([
            'shelter_name' => 'Posko Reject',
            'address' => 'Jl. Test',
            'max_capacity' => 100,
            'current_occupants' => 0,
            'has_toilet_facilities' => 'No',
            'status' => 'active',
            'latitude' => -6.28,
            'longitude' => 107.0,
            'facilities' => ['Dapur Umum'],
        ]);

        $pengelola = User::create([
            'fullname' => 'Pengelola Reject',
            'email' => 'reject@example.com',
            'phone' => '089999999999',
            'password' => Hash::make('password123'),
            'role' => 'Pengelola_Posko',
            'shelter_id' => $shelter->shelter_id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->adminUser)->post(
            route('admin.user.reject', $pengelola->user_id)
        );

        $response->assertRedirect(route('admin.user-verification'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'user_id' => $pengelola->user_id,
            'status' => 'rejected',
        ]);
    }

    public function test_relawan_registration_requires_document_and_nik(): void
    {
        $response = $this->post(route('register.step2.relawan.submit'), [
            'fullname' => 'Relawan Invalid',
            'email' => 'invalid@example.com',
            'phone' => '081000000001',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'keahlian' => ['Medis'],
        ]);

        $response->assertSessionHasErrors(['nik', 'document']);
        $this->assertDatabaseMissing('users', ['email' => 'invalid@example.com']);
    }
}
