<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * PDF Spec: water_level_cm harus DECIMAL(5,2), bukan integer.
     */
    public function up(): void
    {
        Schema::table('water_gates', function (Blueprint $table) {
            $table->decimal('water_level_cm', 5, 2)->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('water_gates', function (Blueprint $table) {
            $table->integer('water_level_cm')->nullable(false)->change();
        });
    }
};
