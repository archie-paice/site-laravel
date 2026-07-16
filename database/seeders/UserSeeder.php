<?php

namespace Database\Seeders;

use App;
use App\Models\Staff;
use App\Models\User;
use DateTime;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (App::environment('local', 'development')) {
            $user = User::firstOrCreate([
                'id' => 10000010,
            ], [
                'first_name' => 'Web',
                'last_name' => 'Ten',
                'email' => 'web10@vatusa.net',
                'rating' => 11,
                'joined_at' => new DateTime,
                'division' => 'USA',
                'facility' => 'ZJX',
                'rostered' => true,
            ]);

            $user->assignRole('admin', 'staff', 'training', 'events', 'facilities', 'instructor', 'core');

            $user = User::firstOrCreate([
                'id' => 10000009,
            ], [
                'first_name' => 'Web',
                'last_name' => 'Nine',
                'email' => 'web09@vatusa.net',
                'rating' => 10,
                'joined_at' => new DateTime,
                'division' => 'USA',
                'facility' => 'ZJX',
                'rostered' => true,
            ]);

            $user->assignRole('admin', 'staff', 'training', 'events', 'facilities', 'instructor', 'core');

            $user = User::firstOrCreate([
                'id' => 10000008,
            ], [
                'first_name' => 'Web',
                'last_name' => 'Eight',
                'email' => 'web08@vatusa.net',
                'rating' => 8,
                'joined_at' => new DateTime,
                'division' => 'USA',
                'facility' => 'ZJX',
                'rostered' => true,
            ]);

            $user->assignRole('admin', 'training', 'staff', 'instructor', 'core');

            Staff::firstOrCreate(['title_short' => 'ATA', 'user_id' => 10000008], [
                'title_long' => 'Training Administrator',
                'primary_contact' => false,
            ]);

            Staff::firstOrCreate(['title_short' => 'INS', 'user_id' => 10000008], [
                'title_long' => 'Instructor',
                'primary_contact' => false,
            ]);
        }
    }
}
