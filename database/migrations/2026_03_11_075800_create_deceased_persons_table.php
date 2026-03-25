<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deceased_persons', function (Blueprint $table) {
            $table->id();
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->date('date_of_death');
            $table->string('place_of_death')->nullable();
            $table->string('cause_of_death');
            $table->string('sex');
            $table->string('civil_status')->nullable();
            $table->string('nationality')->default('Filipino');
            $table->string('religion')->nullable();
            $table->text('address')->nullable();
            $table->integer('age_at_death')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deceased_persons');
    }
};
