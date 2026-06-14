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
     *   - Tambah kolom 'proof_photo' VARCHAR(255) NOT NULL.
     *     (Bukti foto pengiriman donasi agar tidak ada klaim palsu)
     *   - Status enum PDF: ENUM('pending','accepted','delivered') DEFAULT 'pending'.
     *     (Migration lama pakai 'shipped' dan 'received')
     */
    public function up(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->string('proof_photo')
                  ->after('shipping_receipt_no')
                  ->comment('Foto bukti pengiriman atau resi barang donasi');
        });

        // Ganti enum values sesuai PDF
        DB::statement("
            ALTER TABLE donations
            MODIFY `status`
            ENUM('pending', 'accepted', 'delivered')
            NOT NULL DEFAULT 'pending'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropColumn('proof_photo');
        });

        DB::statement("
            ALTER TABLE donations
            MODIFY `status`
            ENUM('pending', 'shipped', 'received')
            NOT NULL DEFAULT 'pending'
        ");
    }
};
