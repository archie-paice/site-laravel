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
            $table->string('github_username')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('manual_contributors', function (Blueprint $table) {
            $table->string('github_username')->nullable(false)->change();
        });
    }
};
