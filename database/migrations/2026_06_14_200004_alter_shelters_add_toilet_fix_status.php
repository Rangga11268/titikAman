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
     *   - Tambah kolom 'has_toilet_facilities' ENUM('Yes','No') DEFAULT 'Yes'.
     *     (Fitur krusial untuk atasi gap posko minim fasilitas sanitasi)
     *   - Status enum: PDF pakai 'closed', migration lama pakai 'inactive'.
     */
    public function up(): void
    {
        Schema::table('shelters', function (Blueprint $table) {
            $table->enum('has_toilet_facilities', ['Yes', 'No'])
                  ->default('Yes')
                  ->after('current_occupants')
                  ->comment('Mengatasi gap posko darurat yang minim fasilitas sanitasi');
        });

        // Ganti nilai enum 'inactive' → 'closed' sesuai PDF
        DB::statement("
            ALTER TABLE shelters
            MODIFY `status`
            ENUM('active', 'full', 'closed')
            NOT NULL DEFAULT 'active'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shelters', function (Blueprint $table) {
            $table->dropColumn('has_toilet_facilities');
        });

        DB::statement("
            ALTER TABLE shelters
            MODIFY `status`
            ENUM('active', 'full', 'inactive')
            NOT NULL DEFAULT 'active'
        ");
    }
};
