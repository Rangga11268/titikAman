<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Shelter;
use App\Models\WaterGate;
use App\Models\FloodReport;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Reset transaction tables to prevent duplicate key collisions and orphans
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \App\Models\RescueMission::truncate();
        \App\Models\SosRequest::truncate();
        \App\Models\FloodReport::truncate();
        \App\Models\Donation::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

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
                'fullname' => 'Admin Relawan',
                'phone' => '081299999999',
                'password' => Hash::make('password'),
                'role' => 'Admin_Relawan',
                'kecamatan' => 'Bekasi Selatan',
                'kelurahan' => 'Jaka Setia',
            ]
        );

        // Team Leads (Ketua Tim per Kecamatan)
        User::updateOrCreate(
            ['email' => 'lead.bekasitimur@example.com'],
            [
                'fullname' => 'Budi Santoso',
                'phone' => '081311111111',
                'password' => Hash::make('password'),
                'role' => 'Admin_Relawan',
                'kecamatan' => 'Bekasi Timur',
                'kelurahan' => 'Margahayu',
            ]
        );
        User::updateOrCreate(
            ['email' => 'lead.jatiasih@example.com'],
            [
                'fullname' => 'Ani Wijaya',
                'phone' => '081322222222',
                'password' => Hash::make('password'),
                'role' => 'Admin_Relawan',
                'kecamatan' => 'Jatiasih',
                'kelurahan' => 'Jatiasih',
            ]
        );
        User::updateOrCreate(
            ['email' => 'lead.rawalumbu@example.com'],
            [
                'fullname' => 'Dodi Pratama',
                'phone' => '081333333333',
                'password' => Hash::make('password'),
                'role' => 'Admin_Relawan',
                'kecamatan' => 'Rawalumbu',
                'kelurahan' => 'Bojong Rawalumbu',
            ]
        );

        // Anggota Tim Reguler (Relawan Lapangan per Kecamatan)
        // Tim Bekasi Timur
        User::updateOrCreate(['email' => 'anggota.bekasitimur1@example.com'], [
            'fullname' => 'Siti Rahmawati', 'phone' => '081341111111', 'password' => Hash::make('password'),
            'role' => 'Relawan', 'kecamatan' => 'Bekasi Timur', 'kelurahan' => 'Duren Jaya', 'status' => 'approved', 'keahlian' => 'Evakuasi, Medis',
        ]);
        User::updateOrCreate(['email' => 'anggota.bekasitimur2@example.com'], [
            'fullname' => 'Ahmad Fauzi', 'phone' => '081342222222', 'password' => Hash::make('password'),
            'role' => 'Relawan', 'kecamatan' => 'Bekasi Timur', 'kelurahan' => 'Aren Jaya', 'status' => 'approved', 'keahlian' => 'Logistik, Dapur Umum',
        ]);
        // Tim Jatiasih
        User::updateOrCreate(['email' => 'anggota.jatiasih1@example.com'], [
            'fullname' => 'Rina Marlina', 'phone' => '081351111111', 'password' => Hash::make('password'),
            'role' => 'Relawan', 'kecamatan' => 'Jatiasih', 'kelurahan' => 'Jatiluhur', 'status' => 'approved', 'keahlian' => 'Medis',
        ]);
        User::updateOrCreate(['email' => 'anggota.jatiasih2@example.com'], [
            'fullname' => 'Hendra Gunawan', 'phone' => '081352222222', 'password' => Hash::make('password'),
            'role' => 'Relawan', 'kecamatan' => 'Jatiasih', 'kelurahan' => 'Jatikramat', 'status' => 'approved', 'keahlian' => 'Evakuasi, Komunikasi',
        ]);
        // Tim Rawalumbu
        User::updateOrCreate(['email' => 'anggota.rawalumbu1@example.com'], [
            'fullname' => 'Fitri Handayani', 'phone' => '081361111111', 'password' => Hash::make('password'),
            'role' => 'Relawan', 'kecamatan' => 'Rawalumbu', 'kelurahan' => 'Bojong Menteng', 'status' => 'approved', 'keahlian' => 'Dapur Umum, Logistik',
        ]);
        User::updateOrCreate(['email' => 'anggota.rawalumbu2@example.com'], [
            'fullname' => 'Agus Permadi', 'phone' => '081362222222', 'password' => Hash::make('password'),
            'role' => 'Relawan', 'kecamatan' => 'Rawalumbu', 'kelurahan' => 'Pengasinan', 'status' => 'approved', 'keahlian' => 'Evakuasi',
        ]);
        // Tim Bekasi Utara
        User::updateOrCreate(['email' => 'lead.bekasiutara@example.com'], [
            'fullname' => 'Rudi Hermawan', 'phone' => '081371111111', 'password' => Hash::make('password'),
            'role' => 'Admin_Relawan', 'kecamatan' => 'Bekasi Utara', 'kelurahan' => 'Harapan Jaya',
        ]);
        User::updateOrCreate(['email' => 'anggota.bekasiutara1@example.com'], [
            'fullname' => 'Dewi Sartika', 'phone' => '081372222222', 'password' => Hash::make('password'),
            'role' => 'Relawan', 'kecamatan' => 'Bekasi Utara', 'kelurahan' => 'Kaliabang Tengah', 'status' => 'approved', 'keahlian' => 'Medis, Evakuasi',
        ]);

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

        // Assign s1 to Pengelola Posko Test
        $pengelola->shelter_id = $s1->shelter_id;
        $pengelola->save();

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
        $pondokLevel = 350.00;
        WaterGate::updateOrCreate(
            ['gate_name' => 'Pintu Air Pondok Gede Permai'],
            [
                'river_name' => 'Sungai Cileungsi',
                'water_level_cm' => $pondokLevel,
                'danger_status' => WaterGate::calculateDangerStatus($pondokLevel),
                'last_updated' => now(),
            ]
        );

        $bekasiLevel = 210.00;
        WaterGate::updateOrCreate(
            ['gate_name' => 'Pintu Air Bekasi Pasar Baru'],
            [
                'river_name' => 'Sungai Bekasi',
                'water_level_cm' => $bekasiLevel,
                'danger_status' => WaterGate::calculateDangerStatus($bekasiLevel),
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
