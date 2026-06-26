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
        Schema::table('users', function (Blueprint $table) {
            $table->string('nik', 16)->nullable()->after('phone');
            $table->string('keahlian')->nullable()->after('nik');
            $table->string('organisasi', 100)->nullable()->after('keahlian');
            $table->string('nip', 18)->nullable()->after('organisasi');
            $table->string('jabatan', 100)->nullable()->after('nip');
            $table->string('unit_kerja', 100)->nullable()->after('jabatan');
            $table->string('document_path', 255)->nullable()->after('unit_kerja');
            $table->foreignId('shelter_id')->nullable()->after('document_path')->constrained('shelters', 'shelter_id')->nullOnDelete();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved')->after('role');
        });

        Schema::table('shelters', function (Blueprint $table) {
            $table->text('facilities')->nullable()->after('has_toilet_facilities');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['shelter_id']);
            $table->dropColumn([
                'nik',
                'keahlian',
                'organisasi',
                'nip',
                'jabatan',
                'unit_kerja',
                'document_path',
                'shelter_id',
                'status'
            ]);
        });

        Schema::table('shelters', function (Blueprint $table) {
            $table->dropColumn('facilities');
        });
    }
};
