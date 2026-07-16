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
        Schema::table('manual_contributors', function (Blueprint $table) {
            $table->string('display_name')->nullable()->after('github_username');
            $table->string('section')->default('fork')->after('display_name');
        });
    }

    public function down(): void
    {
        Schema::table('manual_contributors', function (Blueprint $table) {
            $table->dropColumn(['display_name', 'section']);
        });
    }
};
