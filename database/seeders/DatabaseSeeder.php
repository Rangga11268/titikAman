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
        // 1. Create Users for all 4 roles (idempotent with updateOrCreate)
        $warga = User::updateOrCreate(
            ['email' => 'warga@example.com'],
            [
                'fullname' => 'Warga Test',
                'phone' => '081234567890',
                'password' => Hash::make('password'),
                'role' => 'Warga',
                'kecamatan' => 'Bekasi Timur',
                'kelurahan' => 'Margahayu',
            ]
        );

        $relawan = User::updateOrCreate(
            ['email' => 'relawan@example.com'],
            [
                'fullname' => 'Relawan Test',
                'phone' => '081299999999',
                'password' => Hash::make('password'),
                'role' => 'Relawan',
                'kecamatan' => 'Bekasi Selatan',
                'kelurahan' => 'Jaka Setia',
            ]
        );

        $pengelola = User::updateOrCreate(
            ['email' => 'pengelola@example.com'],
            [
                'fullname' => 'Pengelola Posko Test',
                'phone' => '081211111111',
                'password' => Hash::make('password'),
                'role' => 'Pengelola_Posko',
                'kecamatan' => 'Bekasi Timur',
                'kelurahan' => 'Margahayu',
            ]
        );

        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'fullname' => 'Admin BPBD Test',
                'phone' => '081277777777',
                'password' => Hash::make('password'),
                'role' => 'Admin_BPBD',
                'kecamatan' => null,
                'kelurahan' => null,
            ]
        );

        // 2. Create Active Shelters
        $s1 = Shelter::updateOrCreate(
            ['shelter_name' => 'Masjid Agung Al-Barkah Bekasi'],
            [
                'address' => 'Jl. Veteran No.46, Kel. Margajaya, Kec. Bekasi Selatan',
                'max_capacity' => 500,
                'current_occupants' => 120,
                'has_toilet_facilities' => 'Yes',
                'status' => 'active',
                'latitude' => -6.23490000,
                'longitude' => 106.99940000,
            ]
        );

        $s2 = Shelter::updateOrCreate(
            ['shelter_name' => 'Stadion Patriot Candrabhaga'],
            [
                'address' => 'Jl. Ahmad Yani No.2, Kel. Kayuringin Jaya, Kec. Bekasi Selatan',
                'max_capacity' => 2000,
                'current_occupants' => 450,
                'has_toilet_facilities' => 'Yes',
                'status' => 'active',
                'latitude' => -6.23830000,
                'longitude' => 106.99220000,
            ]
        );

        $s3 = Shelter::updateOrCreate(
            ['shelter_name' => 'Kantor Kelurahan Margahayu'],
            [
                'address' => 'Jl. Kartini No.7, Kel. Margahayu, Kec. Bekasi Timur',
                'max_capacity' => 150,
                'current_occupants' => 150,
                'has_toilet_facilities' => 'Yes',
                'status' => 'full',
                'latitude' => -6.24410000,
                'longitude' => 107.01180000,
            ]
        );

        // Seed Shelter Needs
        \App\Models\ShelterNeed::updateOrCreate(
            ['shelter_id' => $s1->shelter_id, 'item_name' => 'Makanan Siap Saji'],
            ['quantity_need' => 1000, 'quantity_fulfilled' => 400, 'urgency' => 'high']
        );
        \App\Models\ShelterNeed::updateOrCreate(
            ['shelter_id' => $s1->shelter_id, 'item_name' => 'Susu Formula & Balita'],
            ['quantity_need' => 200, 'quantity_fulfilled' => 50, 'urgency' => 'medium']
        );
        \App\Models\ShelterNeed::updateOrCreate(
            ['shelter_id' => $s2->shelter_id, 'item_name' => 'Air Mineral (Dus)'],
            ['quantity_need' => 500, 'quantity_fulfilled' => 150, 'urgency' => 'high']
        );
        \App\Models\ShelterNeed::updateOrCreate(
            ['shelter_id' => $s2->shelter_id, 'item_name' => 'Selimut & Kasur Lipat'],
            ['quantity_need' => 300, 'quantity_fulfilled' => 100, 'urgency' => 'high']
        );
        \App\Models\ShelterNeed::updateOrCreate(
            ['shelter_id' => $s3->shelter_id, 'item_name' => 'Obat-obatan Dasar'],
            ['quantity_need' => 1000, 'quantity_fulfilled' => 800, 'urgency' => 'medium']
        );

        // 3. Create Water Gates
        WaterGate::updateOrCreate(
            ['gate_name' => 'Pintu Air Pondok Gede Permai'],
            [
                'river_name' => 'Sungai Cileungsi',
                'water_level_cm' => 350.00,
                'danger_status' => 'Siaga_2',
                'last_updated' => now(),
            ]
        );

        WaterGate::updateOrCreate(
            ['gate_name' => 'Pintu Air Bekasi Pasar Baru'],
            [
                'river_name' => 'Sungai Bekasi',
                'water_level_cm' => 210.00,
                'danger_status' => 'Normal',
                'last_updated' => now(),
            ]
        );

        // 4. Create Initial Flood Reports
        FloodReport::updateOrCreate(
            ['street_name' => 'Jl. Kartini Raya RT 03/RW 04, Kel. Margahayu, Kec. Bekasi Timur'],
            [
                'user_id' => $warga->user_id,
                'water_height_cm' => 60,
                'latitude' => -6.24250000,
                'longitude' => 107.00220000,
                'photo_evidence' => null,
                'verification_status' => 'verified',
            ]
        );

        FloodReport::updateOrCreate(
            ['street_name' => 'Perumahan Kemang Pratama, Kel. Bojong Rawalumbu, Kec. Rawalumbu'],
            [
                'user_id' => $warga->user_id,
                'water_height_cm' => 120,
                'latitude' => -6.26880000,
                'longitude' => 106.98330000,
                'photo_evidence' => null,
                'verification_status' => 'verified',
            ]
        );

        // 5. Create Initial SOS Requests & Rescue Missions for Relawan portal demo
        $sosWaiting = \App\Models\SosRequest::updateOrCreate(
            [
                'user_id' => $warga->user_id,
                'latitude' => -6.24100000,
                'longitude' => 106.99500000,
            ],
            [
                'people_trapped' => 3,
                'vulnerable_groups_count' => 1,
                'priority_level' => 'high',
                'description' => 'Terjebak banjir di lantai 2, ada lansia.',
                'status' => 'waiting',
            ]
        );

        $sosResolved = \App\Models\SosRequest::updateOrCreate(
            [
                'user_id' => $warga->user_id,
                'latitude' => -6.23000000,
                'longitude' => 106.99000000,
            ],
            [
                'people_trapped' => 1,
                'vulnerable_groups_count' => 0,
                'priority_level' => 'low',
                'description' => 'Air masuk rumah semata kaki, butuh evakuasi mandiri.',
                'status' => 'completed',
            ]
        );

        \App\Models\RescueMission::updateOrCreate(
            ['sos_id' => $sosResolved->sos_id],
            [
                'volunteer_id' => $relawan->user_id,
                'assigned_at' => now()->subMinutes(30),
                'resolved_at' => now()->subMinutes(10),
            ]
        );
    }
}
