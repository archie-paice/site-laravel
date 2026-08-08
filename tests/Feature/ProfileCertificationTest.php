<?php

use App\Models\CertificationFacility;
use App\Models\CertificationLevel;
use App\Models\User;
use App\Models\UserCertification;
use Database\Seeders\PermissionSeeder;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;

beforeEach(fn () => $this->seed(PermissionSeeder::class));

test('a user profile publicly displays their certifications', function () {
    $facility = CertificationFacility::factory()->create(['identifier' => 'ZJX', 'name' => 'Jacksonville']);
    $level = CertificationLevel::factory()->create(['facility_id' => $facility->id, 'name' => 'Ground', 'abbreviation' => 'GND']);

    $user = User::factory()->create();
    UserCertification::create(['user_id' => $user->id, 'certification_level_id' => $level->id]);

    $response = $this->get(route('users.show', ['user' => $user->id]));

    $response->assertStatus(200);
    $response->assertSee('Certifications');
    $response->assertSee('GND');
});

test('a user profile renders when the guard reports authenticated but resolves a null user', function () {
    // Reproduces the intermittent production 500 "Call to a member function hasRole()
    // on null": the auth guard reports check() === true while user() === null, so the
    // @role/@hasrole directives used across the profile pages must be null-safe.
    $user = User::factory()->create();

    $guard = Mockery::mock(Guard::class)->shouldIgnoreMissing(null);
    $guard->shouldReceive('check')->andReturnTrue();
    $guard->shouldReceive('guest')->andReturnFalse();
    $guard->shouldReceive('user')->andReturnNull();

    Auth::extend('null-user', fn () => $guard);
    config(['auth.guards.web.driver' => 'null-user']);
    $this->app['auth']->forgetGuards();

    $this->get(route('users.show', ['user' => $user->id]))->assertStatus(200);
});
