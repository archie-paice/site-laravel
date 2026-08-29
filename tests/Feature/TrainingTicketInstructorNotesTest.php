<?php

use App\Models\TrainingTicket;
use App\Models\User;
use Database\Seeders\PermissionSeeder;

beforeEach(fn () => $this->seed(PermissionSeeder::class));

test('instructor notes are hidden from the student but shown to training staff', function () {
    $staff = User::factory()->create();
    $staff->assignRole('training');
    $student = User::factory()->create(['rostered' => true]);

    $ticket = TrainingTicket::create([
        'user_id' => $student->id,
        'instructor_id' => $staff->id,
        'position' => 'MCO_APP',
        'session_start' => now(),
        'session_end' => now()->addHour(),
        'movements' => 10,
        'score' => 5,
        'notes' => 'Session notes.',
        'instructor_notes' => 'SECRET-INSTRUCTOR-NOTE',
        'location' => 1,
    ]);

    $this->actingAs($student)
        ->get(route('training-tickets.show', $ticket))
        ->assertOk()
        ->assertDontSee('SECRET-INSTRUCTOR-NOTE');

    $this->actingAs($staff)
        ->get(route('training-tickets.show', $ticket))
        ->assertOk()
        ->assertSee('SECRET-INSTRUCTOR-NOTE');
});

test('a training staff member viewing their own ticket does not see instructor notes', function () {
    $instructor = User::factory()->create();
    $student = User::factory()->create(['rostered' => true]);
    $student->assignRole('training'); // student is also a trainer

    $ticket = TrainingTicket::create([
        'user_id' => $student->id,
        'instructor_id' => $instructor->id,
        'position' => 'MCO_APP',
        'session_start' => now(),
        'session_end' => now()->addHour(),
        'movements' => 10,
        'score' => 5,
        'notes' => 'Session notes.',
        'instructor_notes' => 'SECRET-INSTRUCTOR-NOTE',
        'location' => 1,
    ]);

    $this->actingAs($student)
        ->get(route('training-tickets.show', $ticket))
        ->assertOk()
        ->assertDontSee('SECRET-INSTRUCTOR-NOTE')
        ->assertDontSee('Admin Actions');
});
