<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cemetery_plots', function (Blueprint $table) {
            $table->id();
            $table->string('plot_code')->unique();
            $table->string('section')->nullable();
            $table->string('row')->nullable();
            $table->string('column')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->enum('status', ['available', 'occupied', 'reserved'])->default('available');
            $table->foreignId('deceased_id')->nullable()->constrained('deceased_persons')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cemetery_plots');
    }
};
