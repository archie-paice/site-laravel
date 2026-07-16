<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Fixes the OAuth login failure (SQLSTATE[22001] "value too long for type
     * character varying(3)") that occurred when a VATSIM division/subdivision code
     * exceeded 3 characters (e.g. "MENA"). Widens users.division and users.facility
     * from varchar(3) to varchar(10) so those codes fit.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('division', 10)->nullable()->change();
            $table->string('facility', 10)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('division', 3)->nullable()->change();
            $table->string('facility', 3)->nullable()->change();
        });
    }
};
