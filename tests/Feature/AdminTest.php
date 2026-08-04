<?php

use App\Models\User;
use Database\Seeders\PermissionSeeder;

beforeEach(function () {
    $this->seed(PermissionSeeder::class);
});

test('admin page returns 403 if not authorized', function () {
    $response = $this->get(route('admin.index'));

    $response->assertStatus(403);
});

test('allows staff to view page', function () {
    $this->seed(PermissionSeeder::class);
    $user = User::factory()->create();
    $user->assignRole('staff');

    $this->actingAs($user);

    $response = $this->get(route('admin.index'));
    $response->assertStatus(200);
});

test('authenticated but unauthorized users thrown 403', function () {
    $this->seed(PermissionSeeder::class);
    $user = User::factory()->create();
    $user->assignRole('core');

    $this->actingAs($user);

    $response = $this->get(route('admin.index'));
    $response->assertStatus(403);
});
