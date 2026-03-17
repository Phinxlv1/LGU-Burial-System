<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('deceased_persons', function (Blueprint $table) {
            $table->string('middle_name')->nullable()->change();
            $table->date('date_of_birth')->nullable()->change();
            $table->string('sex')->nullable()->change();
            $table->string('address')->nullable()->change();
            $table->string('cause_of_death')->nullable()->change();
        });
    }

    public function down(): void
    {
        //
    }
};
