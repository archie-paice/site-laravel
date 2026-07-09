<?php

use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;

beforeEach(fn () => $this->seed(PermissionSeeder::class));

function fakeVatsimLogin(array $attributes): void
{
    $socialiteUser = (new SocialiteUser())->map($attributes);

    $provider = Mockery::mock(\Laravel\Socialite\Contracts\Provider::class);
    $provider->shouldReceive('user')->andReturn($socialiteUser);

    Socialite::shouldReceive('driver')->with('vatsim')->andReturn($provider);
}

test('login does not overwrite a rostered user\'s VATUSA facility', function () {
    // facility is owned by the roster sync; a null VATSIM subdivision must not blank it.
    $user = User::factory()->create([
        'id' => 999001,
        'facility' => 'ZJX',
        'rostered' => true,
        'first_name' => 'Old',
    ]);

    fakeVatsimLogin([
        'cid' => 999001,
        'first_name' => 'Updated',
        'last_name' => 'Name',
        'email' => 'updated@example.com',
        'division' => 'USA',
        'facility' => null, // VATSIM subdivision null for VATUSA controllers
        'rating' => 5,
    ]);

    $this->get(route('auth.callback'));

    $user->refresh();
    expect($user->facility)->toBe('ZJX');        // preserved, not clobbered
    expect($user->first_name)->toBe('Updated');  // other fields still sync from VATSIM
});

test('a brand-new user can log in with a null subdivision without error', function () {
    fakeVatsimLogin([
        'cid' => 999002,
        'first_name' => 'New',
        'last_name' => 'Controller',
        'email' => 'new@example.com',
        'division' => 'USA',
        'facility' => null,
        'rating' => 2,
    ]);

    $this->get(route('auth.callback'))->assertRedirect();

    $created = User::find(999002);
    expect($created)->not->toBeNull();
    expect($created->facility)->toBeNull();
});
