<?php

use App\Models\User;
use Database\Seeders\PermissionSeeder;

beforeEach(function () {
    $this->seed(PermissionSeeder::class);
});

$routes = [
    'users.show.training-tickets' => 'training-tickets:read',
    'users.show.training-assignments' => 'training-assignments:read',
    'users.show.solo-certs' => 'solo-certs:read',
];

test('guests are redirected to login', function (string $route) {
    $target = User::factory()->create();

    $this->get(route($route, $target))->assertRedirect(route('login'));
})->with(array_keys($routes));

test('authenticated users without the permission get 403', function (string $route) {
    $viewer = User::factory()->create();
    $viewer->assignRole('core'); // no training read permissions
    $target = User::factory()->create();

    $this->actingAs($viewer)->get(route($route, $target))->assertForbidden();
})->with(array_keys($routes));

test('a user can view their own training pages', function (string $route) {
    $user = User::factory()->create(['rostered' => true]);

    $this->actingAs($user)->get(route($route, $user))->assertOk();
})->with(array_keys($routes));

test('training staff can view another users training pages', function (string $route) {
    $staff = User::factory()->create();
    $staff->assignRole('training');
    $target = User::factory()->create(['rostered' => true]);

    $this->actingAs($staff)->get(route($route, $target))->assertOk();
})->with(array_keys($routes));

test('a granular permission does not grant access to other pages', function () {
    $viewer = User::factory()->create();
    $viewer->givePermissionTo('solo-certs:read');
    $target = User::factory()->create(['rostered' => true]);

    $this->actingAs($viewer);

    // Has solo-certs access...
    $this->get(route('users.show.solo-certs', $target))->assertOk();
    // ...but not the other two.
    $this->get(route('users.show.training-tickets', $target))->assertForbidden();
    $this->get(route('users.show.training-assignments', $target))->assertForbidden();
});
