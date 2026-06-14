<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'fullname' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '081234567890',
            'role' => 'Warga',
        ]);
    }
}
