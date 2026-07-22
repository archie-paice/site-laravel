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
        Schema::create('training_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('instructor_id')->constrained('users')->restrictOnDelete();
            $table->timestamp('session_start')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('session_end');
            $table->string('position');
            $table->integer('movements')->default(0);
            $table->integer('score', false, false)->default(1);
            $table->mediumText('notes');
            $table->integer('location');
            $table->boolean('vatusa_synced')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_tickets');
    }
};
