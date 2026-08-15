<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * The descriptions the category seeder used to write. Matching on the exact
     * text means a description someone has since written by hand is left alone;
     * only the untouched generated copy is cleared.
     */
    private const SEEDED_DESCRIPTIONS = [
        'Facility-wide and position-specific standard operating procedures for vZJX controllers.',
        'Operational agreements between vZJX and adjacent facilities governing coordination procedures.',
        'Study guides, training syllabi, and reference materials for controller certification.',
        'Quick reference cards and cheat sheets for use during controlling sessions.',
        'Airspace diagrams, sector maps, and facility charts for ZJX ARTCC.',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('publication_categories')
            ->whereIn('description', self::SEEDED_DESCRIPTIONS)
            ->update(['description' => null]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Deliberately empty. Restoring the placeholder text is never the desired
        // outcome, and once cleared there is no way to tell which categories had
        // it in the first place.
    }
};
