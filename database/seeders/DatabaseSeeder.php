<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(PermissionSeeder $permissionSeeder, UserSeeder $userSeeder, StatisticsPrefixesSeeder $statisticsPrefixes): void
    {
        $permissionSeeder->run();
        $userSeeder->run();
        $statisticsPrefixes->run();
    }
}
