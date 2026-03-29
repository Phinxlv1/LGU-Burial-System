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
    DB::statement("ALTER TABLE burial_permits MODIFY COLUMN status ENUM('pending','approved','released','expired','active','expiring') NOT NULL DEFAULT 'active'");
}

public function down(): void
{
    DB::statement("ALTER TABLE burial_permits MODIFY COLUMN status ENUM('pending','approved','released','expired') NOT NULL DEFAULT 'pending'");
}
};
