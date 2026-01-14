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
            $table->integer('certification_level');
            $table->string('certification_name');
            $table->string('abbreviation');
            $table->boolean('is_default')->default(false);
            $table->unique(['facility_id', 'certification_level'], 'facility_level_unique');
            $table->unique(['facility_id', 'abbreviation'], 'facility_abbreviation_unique');
            $table->unique(['facility_id', 'certification_name'], 'facility_name_unique');
            $table->unique(['facility_id', 'is_default'], 'facility_only_one_default_unique');
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
