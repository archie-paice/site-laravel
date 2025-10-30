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
        Schema::table('users', function(Blueprint $table) {
            $table->boolean('rostered')->default(false);
            $table->integer('rating', false, false)->default(1);
            $table->string('division', 3)->nullable();
            $table->string('facility', 3)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function(Blueprint $table) {
            $table->dropColumn('rostered');
            $table->dropColumn('rating');
            $table->dropColumn('division');
            $table->dropColumn('facility');
        });
    }
};
