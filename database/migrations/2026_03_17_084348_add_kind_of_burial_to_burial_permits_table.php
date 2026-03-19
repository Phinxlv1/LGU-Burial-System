<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('burial_permits', function (Blueprint $table) {
            if (!Schema::hasColumn('burial_permits', 'kind_of_burial')) {
                $table->string('kind_of_burial')->nullable()->after('permit_type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('burial_permits', function (Blueprint $table) {
            $table->dropColumn('kind_of_burial');
        });
    }
};
