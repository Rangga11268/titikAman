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
        Schema::create('donations', function (Blueprint $table) {
            $table->id('donation_id');
            $table->foreignId('donor_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->foreignId('need_id')->constrained('shelter_needs', 'need_id')->onDelete('cascade');
            $table->integer('quantity_donated');
            $table->string('shipping_receipt_no', 100)->nullable();
            $table->string('proof_photo', 255);
            $table->enum('status', ['pending', 'accepted', 'delivered'])->default('pending');
            $table->timestamp('donated_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
