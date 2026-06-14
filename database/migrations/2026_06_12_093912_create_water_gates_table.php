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
            $table->id('gate_id');
            $table->string('gate_name', 100);
            $table->string('river_name', 100);
            $table->decimal('water_level_cm', 5, 2);
            $table->enum('danger_status', ['Normal', 'Siaga_3', 'Siaga_2', 'Siaga_1']);
            $table->timestamp('last_updated')->useCurrent()->useCurrentOnUpdate();
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
