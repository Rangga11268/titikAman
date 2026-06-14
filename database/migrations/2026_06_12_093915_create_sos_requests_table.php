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
        Schema::create('sos_requests', function (Blueprint $table) {
            $table->id('sos_id');
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->decimal('latitude', 18, 8);
            $table->decimal('longitude', 11, 8);
            $table->integer('people_trapped');
            $table->integer('vulnerable_groups_count')->default(0);
            $table->enum('priority_level', ['low', 'medium', 'high'])->default('low');
            $table->text('description')->nullable();
            $table->enum('status', ['waiting', 'assigned', 'rescued', 'completed'])->default('waiting');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sos_requests');
    }
};
