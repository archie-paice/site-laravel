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
        Schema::create('training_assignments', function(Blueprint $table) {
            $table->id();
            $table->foreignId('trainee_id')->constrained('users');
            $table->foreignId('instructor_id')->nullable()->constrained('users');
            $table->boolean('active')->default(true);
            $table->string('training_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_assignments');
    }
};
