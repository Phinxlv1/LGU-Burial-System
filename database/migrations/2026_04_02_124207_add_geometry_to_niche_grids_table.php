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
        Schema::table('niche_grids', function (Blueprint $table) {
            $table->float('rotation')->default(0)->after('longitude');
            $table->float('width_scale')->default(1.0)->after('rotation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('niche_grids', function (Blueprint $table) {
            $table->dropColumn(['rotation', 'width_scale']);
        });
    }
};
