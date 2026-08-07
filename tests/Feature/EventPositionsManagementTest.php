<?php

use App\Enums\EventType;
use App\Livewire\EventPositionsManagement;
use App\Models\Event;
use App\Models\EventPosition;
use App\Models\EventPositionPreset;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(PermissionSeeder::class);
});

test('loading a preset seeds the list without persisting', function () {
    $staff = User::factory()->create();
    $staff->assignRole('staff', 'events');
    $this->actingAs($staff);

    $event = Event::create([
        'title' => 'Test Event',
        'description' => 'desc',
        'start' => now()->addDay(),
        'end' => now()->addDay()->addHours(2),
        'type' => EventType::HOME,
        'featured_fields' => [],
        'hidden' => false,
        'presetPositions' => ['JAX_CTR'],
    ]);

    EventPositionPreset::create(['name' => 'Full Staffing', 'positions' => ['JAX_CTR', 'MCO_APP']]);

    Livewire::test(EventPositionsManagement::class, ['event' => $event])
        ->set('selectedPreset', 'Full Staffing')
        ->call('loadPreset')
        ->assertSet('positions', ['JAX_CTR', 'MCO_APP']);

    expect($event->fresh()->presetPositions)->toBe(['JAX_CTR']);
});

test('save normalizes and persists the list', function () {
    $staff = User::factory()->create();
    $staff->assignRole('staff', 'events');
    $this->actingAs($staff);

    $event = Event::create([
        'title' => 'Test Event',
        'description' => 'desc',
        'start' => now()->addDay(),
        'end' => now()->addDay()->addHours(2),
        'type' => EventType::HOME,
        'featured_fields' => [],
        'hidden' => false,
    ]);

    Livewire::test(EventPositionsManagement::class, ['event' => $event])
        ->set('positions', [' jax_ctr ', 'MCO_APP', 'mco_app'])
        ->call('save')
        ->assertSet('positions', ['JAX_CTR', 'MCO_APP'])
        ->assertSet('updated', true);

    expect($event->fresh()->presetPositions)->toBe(['JAX_CTR', 'MCO_APP']);
});

test('a freeform removal of an assigned position is rejected', function () {
    $staff = User::factory()->create();
    $staff->assignRole('staff', 'events');
    $this->actingAs($staff);

    $event = Event::create([
        'title' => 'Test Event',
        'description' => 'desc',
        'start' => now()->addDay(),
        'end' => now()->addDay()->addHours(2),
        'type' => EventType::HOME,
        'featured_fields' => [],
        'hidden' => false,
        'presetPositions' => ['JAX_CTR', 'MCO_APP'],
    ]);

    EventPosition::create([
        'event_id' => $event->id,
        'user_id' => User::factory()->create()->id,
        'requested_position' => 'MCO_APP',
        'assigned_position' => 'MCO_APP',
        'start' => $event->start,
        'end' => $event->end,
    ]);

    Livewire::test(EventPositionsManagement::class, ['event' => $event])
        ->set('positions', ['JAX_CTR'])
        ->call('save')
        ->assertHasErrors('positions')
        ->assertSet('positions', ['JAX_CTR', 'MCO_APP']);

    expect($event->fresh()->presetPositions)->toBe(['JAX_CTR', 'MCO_APP']);
});

test('loading a narrower preset then saving is rejected and reverts to the saved list', function () {
    $staff = User::factory()->create();
    $staff->assignRole('staff', 'events');
    $this->actingAs($staff);

    $event = Event::create([
        'title' => 'Test Event',
        'description' => 'desc',
        'start' => now()->addDay(),
        'end' => now()->addDay()->addHours(2),
        'type' => EventType::HOME,
        'featured_fields' => [],
        'hidden' => false,
        'presetPositions' => ['JAX_CTR', 'MCO_APP'],
    ]);

    EventPosition::create([
        'event_id' => $event->id,
        'user_id' => User::factory()->create()->id,
        'requested_position' => 'MCO_APP',
        'assigned_position' => 'MCO_APP',
        'start' => $event->start,
        'end' => $event->end,
    ]);

    EventPositionPreset::create(['name' => 'Narrow', 'positions' => ['JAX_CTR']]);

    Livewire::test(EventPositionsManagement::class, ['event' => $event])
        ->set('selectedPreset', 'Narrow')
        ->call('loadPreset')
        ->assertSet('positions', ['JAX_CTR']) // in-memory load succeeds, nothing persisted yet
        ->call('save')
        ->assertHasErrors('positions')
        ->assertSet('positions', ['JAX_CTR', 'MCO_APP']); // reverted to DB truth

    expect($event->fresh()->presetPositions)->toBe(['JAX_CTR', 'MCO_APP']);
});

test('save requires the manage events permission', function () {
    $viewer = User::factory()->create();
    $viewer->assignRole('staff');
    $this->actingAs($viewer);

    $event = Event::create([
        'title' => 'Test Event',
        'description' => 'desc',
        'start' => now()->addDay(),
        'end' => now()->addDay()->addHours(2),
        'type' => EventType::HOME,
        'featured_fields' => [],
        'hidden' => false,
    ]);

    Livewire::test(EventPositionsManagement::class, ['event' => $event])
        ->set('positions', ['JAX_CTR'])
        ->call('save')
        ->assertForbidden();

    expect($event->fresh()->presetPositions)->toBeNull();
});

test('a drifted assignment outside the current list still blocks a save that omits it', function () {
    $staff = User::factory()->create();
    $staff->assignRole('staff', 'events');
    $this->actingAs($staff);

    $event = Event::create([
        'title' => 'Test Event',
        'description' => 'desc',
        'start' => now()->addDay(),
        'end' => now()->addDay()->addHours(2),
        'type' => EventType::HOME,
        'featured_fields' => [],
        'hidden' => false,
        'presetPositions' => ['JAX_CTR'],
    ]);

    // Simulates legacy/drifted data: assigned to a position that was never
    // (or is no longer) on the event's own position list.
    EventPosition::create([
        'event_id' => $event->id,
        'user_id' => User::factory()->create()->id,
        'requested_position' => 'MCO_APP',
        'assigned_position' => 'MCO_APP',
        'start' => $event->start,
        'end' => $event->end,
    ]);

    Livewire::test(EventPositionsManagement::class, ['event' => $event])
        ->set('positions', ['JAX_CTR'])
        ->call('save')
        ->assertHasErrors('positions');

    expect($event->fresh()->presetPositions)->toBe(['JAX_CTR']);
});

test('removing an assigned position is blocked regardless of case', function () {
    $staff = User::factory()->create();
    $staff->assignRole('staff', 'events');
    $this->actingAs($staff);

    $event = Event::create([
        'title' => 'Test Event',
        'description' => 'desc',
        'start' => now()->addDay(),
        'end' => now()->addDay()->addHours(2),
        'type' => EventType::HOME,
        'featured_fields' => [],
        'hidden' => false,
        'presetPositions' => ['JAX_CTR'],
    ]);

    EventPosition::create([
        'event_id' => $event->id,
        'user_id' => User::factory()->create()->id,
        'requested_position' => 'JAX_CTR',
        'assigned_position' => 'JAX_CTR',
        'start' => $event->start,
        'end' => $event->end,
    ]);

    Livewire::test(EventPositionsManagement::class, ['event' => $event])
        ->call('removePosition', 'jax_ctr')
        ->assertSet('positions', [])
        ->call('save')
        ->assertHasErrors('positions');

    expect($event->fresh()->presetPositions)->toBe(['JAX_CTR']);
});
