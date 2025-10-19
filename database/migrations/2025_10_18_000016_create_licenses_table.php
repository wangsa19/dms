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
        Schema::create('licenses', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_id');
            $table->string('name_jp');
            $table->foreignId('document_type_id')->constrained('document_types');
            $table->string('occurrence_type')->nullable();
            $table->foreignId('category_id')->constrained('categories');
            $table->foreignId('field_id')->constrained('fields');
            $table->foreignId('department_id')->constrained('departments');
            $table->foreignId('owner_id')->constrained('employees');
            $table->string('status');
            $table->date('start_date');
            $table->date('end_date');
            $table->date('reminder_date');
            $table->string('government_issuer')->nullable();
            $table->integer('action_frequency_value')->nullable();
            $table->foreignId('action_frequency_unit_id')->nullable()->constrained('action_frequency_units');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licenses');
    }
};
