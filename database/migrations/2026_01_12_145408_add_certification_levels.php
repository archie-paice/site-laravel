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
        Schema::create('certification_levels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facility_id')->constrained('certification_facilities')->cascadeOnDelete();
            $table->integer('level');
            $table->string('name');
            $table->string('abbreviation');
            $table->unique(['facility_id', 'level'], 'facility_level_unique');
            $table->unique(['facility_id', 'abbreviation'], 'facility_abbreviation_unique');
            $table->unique(['facility_id', 'name'], 'facility_name_unique');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certification_levels');
    }
};
