<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('deceased_persons', function (Blueprint $table) {
            if (! Schema::hasColumn('deceased_persons', 'nationality')) {
                $table->string('nationality')->nullable()->after('last_name');
            }
            if (! Schema::hasColumn('deceased_persons', 'age')) {
                $table->unsignedInteger('age')->nullable()->after('nationality');
            }
            if (! Schema::hasColumn('deceased_persons', 'sex')) {
                $table->string('sex')->nullable()->after('age');
            }
            if (! Schema::hasColumn('deceased_persons', 'kind_of_burial')) {
                $table->string('kind_of_burial')->nullable()->after('sex');
            }
        });
    }

    public function down(): void
    {
        Schema::table('deceased_persons', function (Blueprint $table) {
            $table->dropColumn(['nationality', 'age', 'sex', 'kind_of_burial']);
        });
    }
};
