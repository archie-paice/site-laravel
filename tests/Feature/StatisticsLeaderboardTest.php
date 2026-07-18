<?php

use App\Models\ControllerMonthlyStat;
use App\Models\User;
use Database\Seeders\PermissionSeeder;

beforeEach(function () {
    $this->seed(PermissionSeeder::class);
});

test('all-months leaderboard sums each controllers hours in SQL', function () {
    $user = User::factory()->create(['rostered' => true, 'rating' => 5]);

    ControllerMonthlyStat::create([
        'user_id' => $user->id, 'year' => 2025, 'month' => 1,
        'delivery_hours' => 1, 'ground_hours' => 2, 'tower_hours' => 0,
        'approach_hours' => 0, 'center_hours' => 0,
    ]);
    ControllerMonthlyStat::create([
        'user_id' => $user->id, 'year' => 2025, 'month' => 2,
        'delivery_hours' => 3, 'ground_hours' => 0, 'tower_hours' => 0,
        'approach_hours' => 0, 'center_hours' => 0,
    ]);

    $response = $this->get(route('statistics.index', ['year' => 2025, 'month' => 'all']))
        ->assertOk()
        ->assertSee('images/default_profile.jpg'); // avatar rendered for the controller

    $stats = $response->viewData('stats');

    $row = $stats->firstWhere('user_id', $user->id);

    expect($row)->not->toBeNull()
        ->and($row->delivery_hours)->toBe(4.0)
        ->and($row->ground_hours)->toBe(2.0)
        ->and($row->totalHours())->toBe(6.0);

    // One aggregated row per controller, not one row per monthly record.
    expect($stats->where('user_id', $user->id))->toHaveCount(1);
});

test('all-years leaderboard for a specific month collapses each controller to one row', function () {
    $user = User::factory()->create(['rostered' => true, 'rating' => 5]);

    // Same month (March) across two different years.
    ControllerMonthlyStat::create([
        'user_id' => $user->id, 'year' => 2024, 'month' => 3,
        'delivery_hours' => 2, 'ground_hours' => 0, 'tower_hours' => 0,
        'approach_hours' => 0, 'center_hours' => 0,
    ]);
    ControllerMonthlyStat::create([
        'user_id' => $user->id, 'year' => 2025, 'month' => 3,
        'delivery_hours' => 5, 'ground_hours' => 0, 'tower_hours' => 0,
        'approach_hours' => 0, 'center_hours' => 0,
    ]);
    // A different month that must be excluded by the month filter.
    ControllerMonthlyStat::create([
        'user_id' => $user->id, 'year' => 2025, 'month' => 4,
        'delivery_hours' => 100, 'ground_hours' => 0, 'tower_hours' => 0,
        'approach_hours' => 0, 'center_hours' => 0,
    ]);

    $stats = $this->get(route('statistics.index', ['year' => 'all', 'month' => 3]))
        ->assertOk()
        ->viewData('stats');

    // A single aggregated entry, not one row per year.
    expect($stats->where('user_id', $user->id))->toHaveCount(1);

    $row = $stats->firstWhere('user_id', $user->id);
    expect($row->delivery_hours)->toBe(7.0); // 2 + 5 across years, April excluded
});

test('all-time-since reports the earliest year paired with its own month', function () {
    $user = User::factory()->create(['rostered' => true, 'rating' => 5]);

    // Earliest record is Dec 2024; a later record is Mar 2025. MIN(year)=2024 and
    // MIN(month)=3 taken independently would wrongly read "Mar 2024".
    ControllerMonthlyStat::create([
        'user_id' => $user->id, 'year' => 2024, 'month' => 12,
        'delivery_hours' => 1, 'ground_hours' => 0, 'tower_hours' => 0,
        'approach_hours' => 0, 'center_hours' => 0,
    ]);
    ControllerMonthlyStat::create([
        'user_id' => $user->id, 'year' => 2025, 'month' => 3,
        'delivery_hours' => 1, 'ground_hours' => 0, 'tower_hours' => 0,
        'approach_hours' => 0, 'center_hours' => 0,
    ]);

    $allTimeSince = $this->get(route('statistics.index'))
        ->assertOk()
        ->viewData('allTimeSince');

    expect($allTimeSince)->toBe('Dec 2024');
});
