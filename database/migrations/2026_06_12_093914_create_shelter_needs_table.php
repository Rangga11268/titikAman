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
        Schema::create('shelter_needs', function (Blueprint $table) {
            $table->id('need_id');
            $table->foreignId('shelter_id')->constrained('shelters', 'shelter_id')->onDelete('cascade');
            $table->string('item_name', 100);
            $table->integer('quantity_need');
            $table->integer('quantity_fulfilled')->default(0);
            $table->enum('urgency', ['low', 'medium', 'high']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shelter_needs');
    }
};
