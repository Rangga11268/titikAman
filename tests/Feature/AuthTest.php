<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test login page is accessible.
     */
    public function test_login_page_can_be_rendered(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Masuk ke Akun Anda');
    }

    /**
     * Test user can login with email.
     */
    public function test_user_can_login_with_email(): void
    {
        $user = User::create([
            'fullname' => 'Test User',
            'email' => 'test@email.com',
            'phone' => '081234567890',
            'password' => Hash::make('password123'),
            'role' => 'Warga',
            'kecamatan' => 'Bekasi Selatan',
            'kelurahan' => 'Pekayon Jaya',
        ]);

        $response = $this->post('/login', [
            'login_id' => 'test@email.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/dashboard');
    }

    /**
     * Test user can login with phone number.
     */
    public function test_user_can_login_with_phone(): void
    {
        $user = User::create([
            'fullname' => 'Test User',
            'email' => 'test@email.com',
            'phone' => '081234567890',
            'password' => Hash::make('password123'),
            'role' => 'Warga',
            'kecamatan' => 'Bekasi Selatan',
            'kelurahan' => 'Pekayon Jaya',
        ]);

        $response = $this->post('/login', [
            'login_id' => '081234567890',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/dashboard');
    }

    /**
     * Test user registration.
     */
    public function test_warga_can_register(): void
    {
        $response = $this->post('/register/warga', [
            'fullname' => 'New Warga',
            'email' => 'warga@email.com',
            'phone' => '089876543210',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'kecamatan' => 'Jatiasih',
            'kelurahan' => 'Jatiasih',
            'terms' => 'on',
        ]);

        $this->assertDatabaseHas('users', [
            'fullname' => 'New Warga',
            'email' => 'warga@email.com',
            'phone' => '089876543210',
            'role' => 'Warga',
            'kecamatan' => 'Jatiasih',
            'kelurahan' => 'Jatiasih',
        ]);

        $user = User::where('email', 'warga@email.com')->first();
        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/dashboard');
    }

    /**
     * Test validation during registration.
     */
    public function test_registration_requires_domicile(): void
    {
        $response = $this->post('/register/warga', [
            'fullname' => 'New Warga',
            'email' => 'warga@email.com',
            'phone' => '089876543210',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'terms' => 'on',
        ]);

        $response->assertSessionHasErrors(['kecamatan', 'kelurahan']);
        $this->assertGuest();
    }
}
