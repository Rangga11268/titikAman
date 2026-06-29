<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rescue_missions', function (Blueprint $table) {
            $table->dropForeign(['sos_id']);
            $table->dropUnique(['sos_id']);
            $table->foreign('sos_id')->references('sos_id')->on('sos_requests')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('rescue_missions', function (Blueprint $table) {
            $table->dropForeign(['sos_id']);
            $table->unique('sos_id');
            $table->foreign('sos_id')->references('sos_id')->on('sos_requests')->onDelete('cascade');
        });
    }
};
