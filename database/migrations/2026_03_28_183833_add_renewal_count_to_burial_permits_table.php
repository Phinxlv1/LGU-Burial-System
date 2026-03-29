<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('burial_permits', function (Blueprint $table) {
        if (!Schema::hasColumn('burial_permits', 'renewal_count')) {
            $table->unsignedInteger('renewal_count')->default(0)->after('expiry_date');
        }
    });
}

public function down(): void
{
    Schema::table('burial_permits', function (Blueprint $table) {
        $table->dropColumn('renewal_count');
    });
}
};
