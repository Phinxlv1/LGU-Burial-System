<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('deceased_persons', function (Blueprint $table) {
            $table->string('nationality')->nullable()->change();
            $table->integer('age')->nullable()->change();
            $table->string('sex')->nullable()->change();
            $table->string('middle_name')->nullable()->change();
            $table->string('kind_of_burial')->nullable()->change();
            $table->date('date_of_death')->nullable()->change();
        });
    }

    public function down(): void {}
};