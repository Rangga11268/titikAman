<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('flood_reports', function (Blueprint $table) {
            $table->string('kecamatan', 100)->nullable()->after('user_id');
            $table->string('kelurahan', 100)->nullable()->after('kecamatan');
            $table->enum('status_akses_jalan', ['Masih Bisa Dilewati', 'Sulit Dilewati', 'Tidak Bisa Dilewati'])->default('Masih Bisa Dilewati')->after('longitude');
            $table->boolean('listrik_padam')->default(false)->after('status_akses_jalan');
            $table->boolean('air_masih_naik')->default(false)->after('listrik_padam');
            $table->boolean('butuh_evakuasi')->default(false)->after('air_masih_naik');
            $table->boolean('warga_terisolasi')->default(false)->after('butuh_evakuasi');
            $table->text('keterangan_bebas')->nullable()->after('warga_terisolasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('flood_reports', function (Blueprint $table) {
            $table->dropColumn([
                'kecamatan',
                'kelurahan',
                'status_akses_jalan',
                'listrik_padam',
                'air_masih_naik',
                'butuh_evakuasi',
                'warga_terisolasi',
                'keterangan_bebas'
            ]);
        });
    }
};
