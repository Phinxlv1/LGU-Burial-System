<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('burial_permits', function (Blueprint $table) {
            $table->string('applicant_contact')->nullable()->change();
            $table->string('applicant_relationship')->nullable()->change();
            $table->string('applicant_address')->nullable()->change();
            $table->string('issued_by')->nullable()->change();
            $table->string('remarks')->nullable()->change();
        });
    }

    public function down(): void {}
};
