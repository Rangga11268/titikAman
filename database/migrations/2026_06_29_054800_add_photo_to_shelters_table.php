<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shelters', function (Blueprint $table) {
            $table->string('photo', 255)->nullable()->after('facilities');
        });
    }

    public function down(): void
    {
        Schema::table('shelters', function (Blueprint $table) {
            $table->dropColumn('photo');
        });
    }
};
