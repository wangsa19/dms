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
        Schema::create('document_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('documents');
            $table->integer('version_number');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_type')->nullable(); 
            $table->bigInteger('file_size')->nullable(); 
            $table->text('revision_notes')->nullable(); 
            $table->foreignId('uploader_id')->constrained('users');
            $table->index(['document_id', 'version_number']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_versions');
    }
};
