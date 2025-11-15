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
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('instructor_id')->constrained('users');
            $table->timestamp('session_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('position');
            $table->string('duration');
            $table->integer('movements')->default(0);
            $table->integer('score', false, false)->default(1);
            $table->string('notes');
            $table->integer('location');
            $table->integer('ots_status')->default(0);
            $table->boolean('solo_granted')->default(false);
            $table->integer('vatusa_id')->nullable();
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
