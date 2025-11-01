<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'name' => 'staff',
            'guard_name' => 'staff'
        ]);

        Role::table('roles')->insert([
            'name' => 'web_staff',
            'guard_name' => 'web_staff'
        ]);

        Role::table('roles')->insert([
            'name' => 'event_staff',
            'guard_name' => 'event_staff'
        ]);

        Role::table('roles')->insert([
            'name' => 'training_staff',
            'guard_name' => 'training_staff'
        ]);

        Role::table('roles')->insert([
            'name' => 'senior_staff',
            'guard_name' => 'senior_staff'
        ]);

        Role::table('roles')->insert([
            'name' => 'instructors',
            'guard_name' => 'instructors'
        ]);
    }
}
