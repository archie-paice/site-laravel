<?php

use App\Jobs\RemoveUserFromRoster;
use App\Models\ControllerMonthlyStat;
use App\Models\TrainingTicket;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;

beforeEach(function () {
    $this->seed(PermissionSeeder::class);
});

function makeTicket(int $studentId, int $instructorId, string $start, string $end): void
{
    TrainingTicket::withoutSyncingToSearch(function () use ($studentId, $instructorId, $start, $end) {
        TrainingTicket::create([
            'user_id' => $studentId,
            'instructor_id' => $instructorId,
            'session_start' => $start,
            'session_end' => $end,
            'position' => 'JAX_APP',
            'movements' => 0,
            'score' => 1,
            'notes' => 'Test session',
            'location' => 0,
        ]);
    });
}

test('quarterly rows include training hours split into student and instructor time', function () {
    $controller = User::factory()->create(['rostered' => true, 'rating' => 5, 'facility' => config('app.vatusa_facility')]);
    $instructor = User::factory()->create(['rostered' => true, 'rating' => 8, 'facility' => config('app.vatusa_facility')]);

    // Puts both users into the "logged history" table for Q3 2026.
    foreach ([$controller, $instructor] as $user) {
        ControllerMonthlyStat::create([
            'user_id' => $user->id, 'year' => 2026, 'month' => 7,
            'delivery_hours' => 0, 'ground_hours' => 0, 'tower_hours' => 0,
            'approach_hours' => 0, 'center_hours' => 0,
        ]);
    }

    // 2h with controller as student, instructor teaching.
    makeTicket($controller->id, $instructor->id, '2026-07-05 10:00:00', '2026-07-05 12:00:00');
    // 1h with controller as student again.
    makeTicket($controller->id, $instructor->id, '2026-07-06 10:00:00', '2026-07-06 11:00:00');

    $admin = User::factory()->create();
    $admin->assignRole('admin', 'staff');

    $response = $this->actingAs($admin)
        ->get(route('statistics.quarterly', ['year' => 2026, 'quarter' => 3]))
        ->assertOk();

    $rows = $response->viewData('rows');

    $studentRow = $rows->getCollection()->firstWhere('user.id', $controller->id);
    $instructorRow = $rows->getCollection()->firstWhere('user.id', $instructor->id);

    expect($studentRow->training_student)->toBe(3.0)
        ->and($studentRow->training_instructor)->toBe(0.0)
        ->and($instructorRow->training_instructor)->toBe(3.0)
        ->and($instructorRow->training_student)->toBe(0.0);
});

test('a senior staff member can queue removal of flagged controllers', function () {
    Queue::fake();

    $inactive = User::factory()->create(['rostered' => true]);

    $admin = User::factory()->create();
    $admin->assignRole('admin', 'staff');

    $this->actingAs($admin)
        ->post(route('statistics.quarterly.remove'), [
            'user_ids' => [$inactive->id],
            'reason' => 'Inactivity — below quarterly minimum',
        ])
        ->assertRedirect();

    Queue::assertPushed(RemoveUserFromRoster::class, fn ($job) => $job->userId === $inactive->id
        && $job->reason === 'Inactivity — below quarterly minimum');
});

test('removal requires a reason', function () {
    Queue::fake();

    $inactive = User::factory()->create(['rostered' => true]);

    $admin = User::factory()->create();
    $admin->assignRole('admin', 'staff');

    $this->actingAs($admin)
        ->post(route('statistics.quarterly.remove'), ['user_ids' => [$inactive->id]])
        ->assertSessionHasErrors('reason');

    Queue::assertNothingPushed();
});

test('a user without the removal permission is forbidden', function () {
    Queue::fake();

    $inactive = User::factory()->create(['rostered' => true]);

    $staff = User::factory()->create();
    $staff->assignRole('staff'); // has "view dashboard" but not "remove inactive controllers"

    $this->actingAs($staff)
        ->post(route('statistics.quarterly.remove'), [
            'user_ids' => [$inactive->id],
            'reason' => 'Inactivity',
        ])
        ->assertForbidden();

    Queue::assertNothingPushed();
});

test('the removal job de-rosters the user locally on a successful VATUSA call', function () {
    Http::fake([
        '*' => Http::response(['status' => 'OK'], 200),
    ]);

    $user = User::factory()->create([
        'rostered' => true,
        'operating_initials' => 'AB',
        'facility' => config('app.vatusa_facility'),
    ]);

    (new RemoveUserFromRoster($user->id, 'Inactivity'))->handle();

    $user->refresh();

    expect($user->rostered)->toBeFalse()
        ->and($user->operating_initials)->toBe(''); // cleared (accessor upper-cases null to '')
});
