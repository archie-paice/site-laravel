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
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn([
                'created_at',
                'updated_at',
                'hidden',
                'archived',
                'positionsLocked',
                'manualPositionsOpen',
                'start',
                'end',
                'bannerKey',
                'presetPositions',
                'type',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->timestamps();
            $table->boolean('hidden')->default(false);
            $table->dateTime('archived');
            $table->boolean('positionsLocked')->default(false);
            $table->boolean('manualPositionsOpen')->default(false);
            $table->dateTime('start')->nullable();
            $table->dateTime('end')->nullable();
            $table->string('bannerKey')->nullable();
            $table->json('presetPositions')->nullable();
            $table->string('type')->nullable();
        });
    }
};
