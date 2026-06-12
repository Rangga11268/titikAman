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
        Schema::create('water_gates', function (Blueprint $table) {
            $table->id();
            $table->string('gate_name');
            $table->string('river_name');
            $table->integer('water_level_cm');
            $table->enum('danger_status', ['Normal', 'Siaga 3', 'Siaga 2', 'Siaga 1'])->default('Normal');
            $table->timestamp('last_updated')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('water_gates');
    }
};
