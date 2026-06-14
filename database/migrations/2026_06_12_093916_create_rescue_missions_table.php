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
        Schema::create('rescue_missions', function (Blueprint $table) {
            $table->id('mission_id');
            $table->foreignId('sos_id')->unique()->constrained('sos_requests', 'sos_id')->onDelete('cascade');
            $table->foreignId('volunteer_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->timestamp('assigned_at')->useCurrent();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rescue_missions');
    }
};
