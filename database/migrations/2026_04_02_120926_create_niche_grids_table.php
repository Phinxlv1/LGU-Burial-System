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
        Schema::create('niche_grids', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('rows')->default(1);
            $table->integer('cols')->default(1);
            $table->string('label_format')->default('R{row}-C{col}');
            $table->decimal('latitude', 11, 8);
            $table->decimal('longitude', 11, 8);
            $table->float('rotation')->default(0);
            $table->float('width_scale')->default(1.0);
            $table->json('cells')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('niche_grids');
    }
};
