<?php

use App\Models\StaffingRequest;
use App\Models\User;
use Database\Seeders\PermissionSeeder;

beforeEach(function () {
    $this->seed(PermissionSeeder::class);
});

// Submitting a staffing request

test('given a guest, when visiting the staffing request form, then they are redirected to login', function () {
    $response = $this->get(route('staffing-requests.index'));

    $response->assertRedirect(route('login'));
});

test('given an authenticated user, when visiting the staffing request form, then their details are prefilled', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('staffing-requests.index'));

    $response->assertOk();
    $response->assertSee($user->name);
    $response->assertSee($user->email);
});

test('given an authenticated user, when submitting a valid staffing request, then it is stored against them', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('staffing-requests.store'), [
        'name' => 'Jacksonville Fly-In',
        'description' => 'Looking for JAX_TWR and JAX_APP staffed from 2300z-0200z.',
    ]);

    $response->assertRedirect(route('staffing-requests.index'));
    $response->assertSessionHas('success');

    $staffingRequest = StaffingRequest::sole();
    expect($staffingRequest->user_id)->toBe($user->id)
        ->and($staffingRequest->name)->toBe('Jacksonville Fly-In')
        ->and($staffingRequest->description)->toBe('Looking for JAX_TWR and JAX_APP staffed from 2300z-0200z.');
});

test('given a guest, when submitting a staffing request, then they are redirected to login and nothing is stored', function () {
    $response = $this->post(route('staffing-requests.store'), [
        'name' => 'Jacksonville Fly-In',
        'description' => 'Should not be stored.',
    ]);

    $response->assertRedirect(route('login'));
    expect(StaffingRequest::count())->toBe(0);
});

test('given an authenticated user, when submitting without an event name, then a name error is returned and nothing is stored', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('staffing-requests.store'), [
        'name' => '',
        'description' => 'Some description.',
    ]);

    $response->assertSessionHasErrors('name');
    expect(StaffingRequest::count())->toBe(0);
});

test('given an authenticated user, when submitting without a description, then a description error is returned and nothing is stored', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('staffing-requests.store'), [
        'name' => 'Jacksonville Fly-In',
        'description' => '',
    ]);

    $response->assertSessionHasErrors('description');
    expect(StaffingRequest::count())->toBe(0);
});

// Managing staffing requests

test('given an events staff member, when visiting the management page, then submitted requests are shown', function () {
    $staff = User::factory()->create();
    $staff->assignRole(['staff', 'events']);

    $staffingRequest = StaffingRequest::factory()->create(['name' => 'Coastal Cruise']);

    $response = $this->actingAs($staff)->get(route('admin.staffing-requests.index'));

    $response->assertOk();
    $response->assertSee('Coastal Cruise');
    $response->assertSee($staffingRequest->user->name);
});

test('given a user without the staffing-requests:read permission, when visiting the management page, then the request is forbidden', function () {
    $user = User::factory()->create();
    $user->givePermissionTo('view dashboard');

    $response = $this->actingAs($user)->get(route('admin.staffing-requests.index'));

    $response->assertForbidden();
});

test('given an events staff member, when viewing a staffing request, then the submitter details and description are shown', function () {
    $staff = User::factory()->create();
    $staff->assignRole(['staff', 'events']);

    $staffingRequest = StaffingRequest::factory()->create([
        'name' => 'Coastal Cruise',
        'description' => 'Please staff JAX_GND and JAX_TWR.',
    ]);

    $response = $this->actingAs($staff)->get(route('admin.staffing-requests.show', $staffingRequest));

    $response->assertOk();
    $response->assertSee('Coastal Cruise');
    $response->assertSee('Please staff JAX_GND and JAX_TWR.');
    $response->assertSee($staffingRequest->user->name);
    $response->assertSee($staffingRequest->user->email);
});

test('given an events staff member, when closing a staffing request, then it is deleted', function () {
    $staff = User::factory()->create();
    $staff->assignRole(['staff', 'events']);

    $staffingRequest = StaffingRequest::factory()->create();

    $response = $this->actingAs($staff)->delete(route('admin.staffing-requests.destroy', $staffingRequest));

    $response->assertRedirect(route('admin.staffing-requests.index'));
    $response->assertSessionHas('success');
    expect(StaffingRequest::count())->toBe(0);
});

test('given a user with only the staffing-requests:read permission, when closing a staffing request, then the request is forbidden and it is kept', function () {
    $user = User::factory()->create();
    $user->givePermissionTo(['view dashboard', 'staffing-requests:read']);

    $staffingRequest = StaffingRequest::factory()->create();

    $response = $this->actingAs($user)->delete(route('admin.staffing-requests.destroy', $staffingRequest));

    $response->assertForbidden();
    expect(StaffingRequest::count())->toBe(1);
});

test('given a user with only the staffing-requests:read permission, when visiting the management page, then requests are shown without the close action', function () {
    $user = User::factory()->create();
    $user->givePermissionTo(['view dashboard', 'staffing-requests:read']);

    StaffingRequest::factory()->create(['name' => 'Coastal Cruise']);

    $response = $this->actingAs($user)->get(route('admin.staffing-requests.index'));

    $response->assertOk();
    $response->assertSee('Coastal Cruise');
    // The close form is the only DELETE form on this page.
    $response->assertDontSee('name="_method" value="DELETE"', false);
});

test('given an events staff member, when visiting the management page, then the close action is available', function () {
    $staff = User::factory()->create();
    $staff->assignRole(['staff', 'events']);

    StaffingRequest::factory()->create(['name' => 'Coastal Cruise']);

    $response = $this->actingAs($staff)->get(route('admin.staffing-requests.index'));

    $response->assertOk();
    $response->assertSee('name="_method" value="DELETE"', false);
});

test('given an events staff member, when searching staffing requests, then only matching entries are shown', function () {
    $staff = User::factory()->create();
    $staff->assignRole(['staff', 'events']);

    StaffingRequest::factory()->create(['name' => 'Zuluair Arrival Bash']);
    StaffingRequest::factory()->create(['name' => 'Yankeetown Departure Rush']);

    $response = $this->actingAs($staff)->get(route('admin.staffing-requests.index', ['search' => 'Zuluair']));

    $response->assertOk();
    $response->assertSee('Zuluair Arrival Bash');
    $response->assertDontSee('Yankeetown Departure Rush');
});
