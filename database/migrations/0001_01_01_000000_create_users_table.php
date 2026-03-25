<?php

// ============================================================
// UPDATED MIGRATION
// File: database/migrations/0001_01_01_000000_create_users_table.php
//
// Deceased fields kept (matching 1986 logbook + Image 1):
//   No. → id (auto)
//   Name of Deceased → last_name, first_name, middle_name
//   Date of Death → date_of_death
//   Date Applied → created_at (auto)
//   Date Expired → computed (created_at + 5 years), no column needed
//   Requesting Party → stored on burial_permits.requestor_name
//   Address → stored on burial_permits.requestor_address
//   Kind of Burial → kind_of_burial
//   Amount → stored on burial_permits.amount_paid
//   Balance → stored on burial_permits.balance
//   Contact No. → stored on burial_permits.requestor_contact
//
// REMOVED from deceased_persons: nationality, age, sex,
//   civil_status, religion, date_of_birth, place_of_death,
//   cause_of_death, age_at_death
// ============================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── employees (referenced by users) ──────────────────────
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_number')->unique();
            $table->timestamps();
        });

        // ── users ─────────────────────────────────────────────────
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('employee_number');
            $table->string('name');
            $table->string('rank')->nullable();
            $table->string('gender')->nullable();
            $table->date('birthday')->nullable();
            $table->string('email')->unique();
            $table->string('contact_number')->nullable();
            $table->string('address')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->foreign('employee_number')
                ->references('employee_number')
                ->on('employees')
                ->onDelete('cascade');
        });

        // ── password reset tokens ─────────────────────────────────
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // ── sessions ──────────────────────────────────────────────
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        // ── deceased_persons ──────────────────────────────────────
        // Stripped to 1986 logbook essentials only
        Schema::create('deceased_persons', function (Blueprint $table) {
            $table->id();
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();  // kept as requested
            $table->date('date_of_death');
            $table->string('kind_of_burial')->nullable(); // TOMB / GRAVE-INFANT / NICHES / BONE NICHES
            $table->timestamps();
        });

        // ── burial_permits ────────────────────────────────────────
        // Holds all request/payment info from Image 1 & yellow slip
        Schema::create('burial_permits', function (Blueprint $table) {
            $table->id();
            $table->string('permit_number')->unique();
            $table->foreignId('deceased_id')->constrained('deceased_persons')->onDelete('cascade');

            // Permit meta
            $table->enum('permit_type', ['new', 'renewal'])->default('new');
            $table->enum('status', ['pending', 'approved', 'released', 'expired'])->default('pending');

            // Requestor info (from Image 1 & yellow slip)
            $table->string('requestor_name');
            $table->string('requestor_relation')->nullable();   // e.g. MOTHER, FATHER
            $table->string('requestor_address')->nullable();
            $table->string('requestor_contact')->nullable();

            // Deceased info needed on permit (from yellow slip)
            $table->string('nationality')->nullable();
            $table->unsignedTinyInteger('age')->nullable();
            $table->string('sex')->nullable();
            $table->date('date_of_death')->nullable(); // denormalized for quick print

            // Fee info (from yellow slip & Image 1)
            $table->string('burial_fee_type')->nullable();      // cemented / niche_1st / etc.
            $table->decimal('amount_paid', 10, 2)->nullable();
            $table->decimal('balance', 10, 2)->nullable();

            // OR / payment receipt (Image 1 bottom)
            $table->string('or_number')->nullable();
            $table->date('paid_on')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('burial_permits');
        Schema::dropIfExists('deceased_persons');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
        Schema::dropIfExists('employees');
    }
};
