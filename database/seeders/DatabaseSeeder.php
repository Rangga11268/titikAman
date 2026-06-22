<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Shelter;
use App\Models\WaterGate;
use App\Models\FloodReport;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Users for all 4 roles
        $warga = User::create([
            'fullname' => 'Warga Test',
            'email' => 'warga@example.com',
            'phone' => '081234567890',
            'password' => Hash::make('password'),
            'role' => 'Warga',
            'kecamatan' => 'Bekasi Timur',
            'kelurahan' => 'Margahayu',
        ]);

        $relawan = User::create([
            'fullname' => 'Relawan Test',
            'email' => 'relawan@example.com',
            'phone' => '081299999999',
            'password' => Hash::make('password'),
            'role' => 'Relawan',
            'kecamatan' => 'Bekasi Selatan',
            'kelurahan' => 'Jaka Setia',
        ]);

        $pengelola = User::create([
            'fullname' => 'Pengelola Posko Test',
            'email' => 'pengelola@example.com',
            'phone' => '081211111111',
            'password' => Hash::make('password'),
            'role' => 'Pengelola_Posko',
            'kecamatan' => 'Bekasi Timur',
            'kelurahan' => 'Margahayu',
        ]);

        $admin = User::create([
            'fullname' => 'Admin BPBD Test',
            'email' => 'admin@example.com',
            'phone' => '081277777777',
            'password' => Hash::make('password'),
            'role' => 'Admin_BPBD',
            'kecamatan' => null,
            'kelurahan' => null,
        ]);

        // 2. Create Active Shelters
        Shelter::create([
            'shelter_name' => 'Masjid Agung Al-Barkah Bekasi',
            'address' => 'Jl. Veteran No.46, Kel. Margajaya, Kec. Bekasi Selatan',
            'max_capacity' => 500,
            'current_occupants' => 120,
            'has_toilet_facilities' => 'Yes',
            'status' => 'active',
            'latitude' => -6.23490000,
            'longitude' => 106.99940000,
        ]);

        Shelter::create([
            'shelter_name' => 'Stadion Patriot Candrabhaga',
            'address' => 'Jl. Ahmad Yani No.2, Kel. Kayuringin Jaya, Kec. Bekasi Selatan',
            'max_capacity' => 2000,
            'current_occupants' => 450,
            'has_toilet_facilities' => 'Yes',
            'status' => 'active',
            'latitude' => -6.23830000,
            'longitude' => 106.99220000,
        ]);

        Shelter::create([
            'shelter_name' => 'Kantor Kelurahan Margahayu',
            'address' => 'Jl. Kartini No.7, Kel. Margahayu, Kec. Bekasi Timur',
            'max_capacity' => 150,
            'current_occupants' => 150,
            'has_toilet_facilities' => 'Yes',
            'status' => 'full',
            'latitude' => -6.24410000,
            'longitude' => 107.01180000,
        ]);

        // 3. Create Water Gates
        WaterGate::create([
            'gate_name' => 'Pintu Air Pondok Gede Permai',
            'river_name' => 'Sungai Cileungsi',
            'water_level_cm' => 350.00,
            'danger_status' => 'Siaga_2',
            'last_updated' => now(),
        ]);

        WaterGate::create([
            'gate_name' => 'Pintu Air Bekasi Pasar Baru',
            'river_name' => 'Sungai Bekasi',
            'water_level_cm' => 210.00,
            'danger_status' => 'Normal',
            'last_updated' => now(),
        ]);

        // 4. Create Initial Flood Reports
        FloodReport::create([
            'user_id' => $warga->user_id,
            'water_height_cm' => 60,
            'street_name' => 'Jl. Kartini Raya RT 03/RW 04, Kel. Margahayu, Kec. Bekasi Timur',
            'latitude' => -6.24250000,
            'longitude' => 107.00220000,
            'photo_evidence' => null,
            'verification_status' => 'verified',
        ]);

        FloodReport::create([
            'user_id' => $warga->user_id,
            'water_height_cm' => 120,
            'street_name' => 'Perumahan Kemang Pratama, Kel. Bojong Rawalumbu, Kec. Rawalumbu',
            'latitude' => -6.26880000,
            'longitude' => 106.98330000,
            'photo_evidence' => null,
            'verification_status' => 'verified',
        ]);
    }
}
