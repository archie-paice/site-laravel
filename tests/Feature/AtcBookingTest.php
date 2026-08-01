<?php

use App\Models\AtcBooking;
use App\Models\User;
use Database\Seeders\PermissionSeeder;

beforeEach(function () {
    $this->seed(PermissionSeeder::class);
});

test('given a rostered user, when booking a future session, then the booking is created', function () {
    $user = User::factory()->create();
    $user->assignRole('rostered');

    $response = $this->actingAs($user)->post(route('bookings.store'), [
        'position' => 'JAX_CTR',
        'start' => now()->addHour()->format('Y-m-d\TH:i'),
        'end' => now()->addHours(3)->format('Y-m-d\TH:i'),
    ]);

    $response->assertRedirect(route('home'));
    $response->assertSessionHas('success');
    expect(AtcBooking::where('user_id', $user->id)->count())->toBe(1);
});

test('given a rostered user, when booking a session starting in the past, then a start error is returned and no booking is created', function () {
    $user = User::factory()->create();
    $user->assignRole('rostered');

    $response = $this->actingAs($user)->post(route('bookings.store'), [
        'position' => 'JAX_CTR',
        'start' => now()->subHour()->format('Y-m-d\TH:i'),
        'end' => now()->addHour()->format('Y-m-d\TH:i'),
    ]);

    $response->assertSessionHasErrors('start');
    expect(AtcBooking::count())->toBe(0);
});

test('given a rostered user, when booking a session longer than five hours, then an end error is returned and no booking is created', function () {
    $user = User::factory()->create();
    $user->assignRole('rostered');

    $response = $this->actingAs($user)->post(route('bookings.store'), [
        'position' => 'JAX_CTR',
        'start' => now()->addHour()->format('Y-m-d\TH:i'),
        'end' => now()->addHours(7)->format('Y-m-d\TH:i'),
    ]);

    $response->assertSessionHasErrors('end');
    expect(AtcBooking::count())->toBe(0);
});

test('given a user without the rostered role, when booking a session, then the request is forbidden', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('bookings.store'), [
        'position' => 'JAX_CTR',
        'start' => now()->addHour()->format('Y-m-d\TH:i'),
        'end' => now()->addHours(2)->format('Y-m-d\TH:i'),
    ]);

    $response->assertForbidden();
    expect(AtcBooking::count())->toBe(0);
});
