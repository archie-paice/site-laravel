<?php

namespace Database\Seeders;

use App;
use App\Models\User;
use DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
                'id' => 10000010
            ], [
                'first_name' => 'Web',
                'last_name' => 'Ten',
                'email' => 'web10@vatusa.net',
                'rating' => 11,
                'joined_at' => new DateTime(),
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
                'joined_at' => new DateTime(),
                'division' => 'USA',
                'facility' => 'ZJX',
                'rostered' => true,
            ]);

            $user->assignRole('admin', 'staff', 'training', 'events', 'facilities', 'instructor', 'core');
        };
    }
}
