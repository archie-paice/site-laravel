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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->enum('type', [
                'HOME',
                'SUPPORT_REQUIRED',
                'SUPPORT_OPTIONAL',
                'GROUP_FLIGHT',
                'FRIDAY_NIGHT_OPERATIONS',
                'SATURDAY_NIGHT_OPERATIONS',
                'TRAINING',
            ]);
            $table->boolean('hidden');
            $table->boolean('positionsLocked');
            $table->boolean('manualPositionsOpen');
            $table->timestamp('archived');
            $table->timestamp('start');
            $table->timestamp('end');
            $table->text('bannerKey');
            $table->json('featuredFields')->nullable();
            $table->json('presetPositions')->nullable();
            $table->text('description');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
