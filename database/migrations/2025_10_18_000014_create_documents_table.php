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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('document_type_id')->constrained('document_types');
            $table->foreignId('category_id')->constrained('categories');
            $table->foreignId('field_id')->constrained('fields');
            $table->foreignId('department_id')->constrained('departments');
            $table->foreignId('section_id')->constrained('sections');
            $table->foreignId('owner_id')->constrained('employees');
            $table->string('location');
            $table->string('call_number')->nullable();
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
