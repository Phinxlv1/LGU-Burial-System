<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Drop ALL existing import_logs variants (handles any old broken table)
        Schema::dropIfExists('import_logs');

        Schema::create('import_logs', function (Blueprint $table) {
            $table->id();
            $table->string('file_name');
            $table->unsignedBigInteger('uploaded_by')->nullable();
            $table->integer('total_rows')->default(0);
            $table->integer('imported')->default(0);
            $table->integer('skipped')->default(0);
            $table->json('skip_reasons')->nullable();
            $table->timestamps();

            $table->foreign('uploaded_by')
                  ->references('id')
                  ->on('users')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('import_logs');
    }
};