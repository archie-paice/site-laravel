<?php

use App\Models\User;
use Database\Seeders\PermissionSeeder;

test('admin page returns 403 if not authorized', function () {
    $response = $this->get(route('admin.index'));

    $response->assertStatus(403);
});

test('allows staff to view page', function () {
    $user = User::factory()->create();
    $this->seed(PermissionSeeder::class);
    $user->assignRole('staff');

    $this->actingAs($user);

    $response = $this->get(route('admin.index'));
    $response->assertStatus(200);
});

test('authenticated but unauthorized users thrown 403', function () {
    $user = User::factory()->create();
    $this->seed(PermissionSeeder::class);
    $user->assignRole('core');

    $this->actingAs($user);

    $response = $this->get(route('admin.index'));
    $response->assertStatus(403);
});