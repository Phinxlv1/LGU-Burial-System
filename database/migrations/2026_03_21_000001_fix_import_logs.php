<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop whatever broken version exists and start clean
        Schema::dropIfExists('import_logs');

        Schema::create('import_logs', function (Blueprint $table) {
            $table->id();
            $table->string('file_name');
            $table->unsignedBigInteger('uploaded_by')->nullable();
            $table->foreign('uploaded_by')->references('id')->on('users')->nullOnDelete();
            $table->integer('total_rows')->default(0);
            $table->integer('imported')->default(0);
            $table->integer('skipped')->default(0);
            $table->json('skip_reasons')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('import_logs');
    }
};
