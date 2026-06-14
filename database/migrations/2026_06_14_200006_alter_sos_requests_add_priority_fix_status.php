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
     *   - Tambah kolom 'priority_level' ENUM('low','medium','high') DEFAULT 'low'.
     *     (Menentukan tingkat urgensi misi berdasarkan kerentanan korban)
     *   - Status enum PDF: ENUM('waiting','assigned','rescued','completed') DEFAULT 'waiting'.
     *     (Migration lama hanya pakai 'resolved' sebagai status akhir,
     *      PDF memisahkan 'rescued' = sudah tiba di posko & 'completed' = misi selesai dicatat)
     *
     * Catatan: Kolom elderly_count, infant_count, pregnant_count DIPERTAHANKAN
     *   karena lebih granular dari vulnerable_groups_count di PDF dan
     *   dibutuhkan untuk filter prioritas otomatis sistem.
     */
    public function up(): void
    {
        Schema::table('sos_requests', function (Blueprint $table) {
            $table->enum('priority_level', ['low', 'medium', 'high'])
                  ->default('low')
                  ->after('people_trapped')
                  ->comment('Ditentukan otomatis berdasarkan keberadaan kelompok rentan');
        });

        // Ganti enum status sesuai PDF: tambah 'rescued' & 'completed', hapus 'resolved'
        DB::statement("
            ALTER TABLE sos_requests
            MODIFY `status`
            ENUM('waiting', 'assigned', 'rescued', 'completed')
            NOT NULL DEFAULT 'waiting'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sos_requests', function (Blueprint $table) {
            $table->dropColumn('priority_level');
        });

        DB::statement("
            ALTER TABLE sos_requests
            MODIFY `status`
            ENUM('waiting', 'assigned', 'resolved')
            NOT NULL DEFAULT 'waiting'
        ");
    }
};
