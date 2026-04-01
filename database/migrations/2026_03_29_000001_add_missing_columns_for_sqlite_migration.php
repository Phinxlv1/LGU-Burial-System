<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── burial_permits ──────────────────────────────────────────────────
        if (!Schema::hasColumn('burial_permits', 'permit_type')) {
            Schema::table('burial_permits', function (Blueprint $table) {
                $table->string('permit_type', 50)->nullable()->after('updated_at');
            });
        }
        if (!Schema::hasColumn('burial_permits', 'kind_of_burial')) {
            Schema::table('burial_permits', function (Blueprint $table) {
                $table->string('kind_of_burial')->nullable()->after('permit_type');
            });
        }
        if (!Schema::hasColumn('burial_permits', 'issued_by')) {
            Schema::table('burial_permits', function (Blueprint $table) {
                $table->string('issued_by')->nullable()->after('kind_of_burial');
            });
        }

        // ── deceased_persons ────────────────────────────────────────────────
        if (!Schema::hasColumn('deceased_persons', 'name_extension')) {
            Schema::table('deceased_persons', function (Blueprint $table) {
                $table->string('name_extension')->nullable()->after('middle_name');
            });
        }
        if (!Schema::hasColumn('deceased_persons', 'name_number')) {
            Schema::table('deceased_persons', function (Blueprint $table) {
                $table->string('name_number')->nullable()->after('name_extension');
            });
        }
    }

    public function down(): void
    {
        Schema::table('burial_permits', function (Blueprint $table) {
            $table->dropColumn(['permit_type', 'kind_of_burial', 'issued_by']);
        });

        Schema::table('deceased_persons', function (Blueprint $table) {
            $table->dropColumn(['name_extension', 'name_number']);
        });
    }
};