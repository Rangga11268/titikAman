<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * PDF Spec:
     *   - Kolom bernama 'verification_status', bukan 'status'.
     *   - Enum: ENUM('pending', 'verified', 'rejected') DEFAULT 'pending'.
     *     (Migration lama pakai 'waiting', PDF pakai 'pending')
     */
    public function up(): void
    {
        // Ubah enum values + rename kolom sekaligus via raw SQL
        // karena renameColumn + change() tidak bisa dichain di satu statement
        DB::statement("
            ALTER TABLE flood_reports
            CHANGE `status` `verification_status`
            ENUM('pending', 'verified', 'rejected')
            NOT NULL DEFAULT 'pending'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("
            ALTER TABLE flood_reports
            CHANGE `verification_status` `status`
            ENUM('waiting', 'verified', 'rejected')
            NOT NULL DEFAULT 'waiting'
        ");
    }
};
