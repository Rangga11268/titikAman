<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * PDF Spec: role ENUM('Warga', 'Relawan', 'Pengelola_Posko', 'Admin_BPBD')
     * Migration awal pakai lowercase dan nama singkat (pengelola, admin).
     *
     * PENTING: Setelah migration ini dijalankan, semua middleware, policy, Gate,
     * dan seeder yang memeriksa role HARUS menggunakan nilai baru ini.
     * Contoh: Auth::user()->role === 'Pengelola_Posko'
     */
    public function up(): void
    {
        // Migrasi data lama ke format baru sebelum mengubah enum
        DB::statement("UPDATE users SET role = 'Warga' WHERE role = 'warga'");
        DB::statement("UPDATE users SET role = 'Relawan' WHERE role = 'relawan'");
        DB::statement("UPDATE users SET role = 'Pengelola_Posko' WHERE role = 'pengelola'");
        DB::statement("UPDATE users SET role = 'Admin_BPBD' WHERE role = 'admin'");

        // Ubah definisi enum ke PDF spec
        DB::statement("
            ALTER TABLE users
            MODIFY `role`
            ENUM('Warga', 'Relawan', 'Pengelola_Posko', 'Admin_BPBD')
            NOT NULL DEFAULT 'Warga'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("UPDATE users SET role = 'warga' WHERE role = 'Warga'");
        DB::statement("UPDATE users SET role = 'relawan' WHERE role = 'Relawan'");
        DB::statement("UPDATE users SET role = 'pengelola' WHERE role = 'Pengelola_Posko'");
        DB::statement("UPDATE users SET role = 'admin' WHERE role = 'Admin_BPBD'");

        DB::statement("
            ALTER TABLE users
            MODIFY `role`
            ENUM('warga', 'relawan', 'pengelola', 'admin')
            NOT NULL DEFAULT 'warga'
        ");
    }
};
