<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * PDF Spec: nama kolom adalah 'quantity_need' (bukan 'quantity_needed').
     * Migration awal salah tulis dengan suffix -ed.
     */
    public function up(): void
    {
        Schema::table('shelter_needs', function (Blueprint $table) {
            $table->renameColumn('quantity_needed', 'quantity_need');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shelter_needs', function (Blueprint $table) {
            $table->renameColumn('quantity_need', 'quantity_needed');
        });
    }
};
