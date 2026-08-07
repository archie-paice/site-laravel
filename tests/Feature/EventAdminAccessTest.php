<?php

use App\Enums\EventType;
use App\Livewire\EventRegistrants;
use App\Models\Event;
use App\Models\EventPosition;
use App\Models\EventPositionPreset;
use App\Models\FeaturedField;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(PermissionSeeder::class);
});

function makeEvent(array $attributes = []): Event
{
    return Event::create(array_merge([
        'title' => 'Test Event',
        'description' => 'desc',
        'start' => now()->addDay(),
        'end' => now()->addDay()->addHours(2),
        'type' => EventType::HOME,
        'featured_fields' => [],
        'hidden' => false,
    ], $attributes));
}

function makeEventsStaff(): User
{
    $user = User::factory()->create();
    $user->assignRole('staff', 'events');

    return $user;
}

test('guests cannot read another users event registrations', function () {
    $target = User::factory()->create();

    $this->get(route('users.show.registered-events', $target))
        ->assertRedirect(route('login'));
});

test('a user cannot read another users event registrations', function () {
    $viewer = User::factory()->create();
    $viewer->assignRole('core');

    $this->actingAs($viewer)
        ->get(route('users.show.registered-events', User::factory()->create()))
        ->assertForbidden();
});

test('a user can read their own event registrations', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('users.show.registered-events', $user))
        ->assertOk();
});

test('events staff can read another users event registrations', function () {
    $this->actingAs(makeEventsStaff())
        ->get(route('users.show.registered-events', User::factory()->create()))
        ->assertOk();
});

test('news management is limited to admins', function () {
    $staff = User::factory()->create();
    $staff->assignRole('staff');

    $this->actingAs($staff)->get(route('admin.news.index'))->assertForbidden();
    $this->actingAs($staff)->post(route('admin.news.store'), [
        'title' => 'Sneaky',
        'content' => 'Should not publish',
    ])->assertForbidden();

    $admin = User::factory()->create();
    $admin->assignRole('staff', 'admin');

    $this->actingAs($admin)->get(route('admin.news.index'))->assertOk();
});

test('the event field preset page lists and accepts new fields', function () {
    $this->actingAs(makeEventsStaff());

    FeaturedField::create(['name' => 'KJAX']);

    $this->get(route('admin.events.event-fields.index'))
        ->assertOk()
        ->assertSee('KJAX');

    $this->post(route('admin.events.event-fields.store'), ['name' => 'kmco'])
        ->assertRedirect(route('admin.events.event-fields.index'));

    expect(FeaturedField::where('name', 'KMCO')->exists())->toBeTrue();
});

test('the edit form keeps the events existing start time', function () {
    $this->actingAs(makeEventsStaff());

    $event = makeEvent(['start' => now()->addDay()->setTime(18, 30)]);

    $this->get(route('admin.events.edit', ['event' => $event->id]))
        ->assertOk()
        ->assertSee($event->start->format('Y-m-d\TH:i'));
});

test('a position preset can be applied after the event is created', function () {
    $this->actingAs(makeEventsStaff());

    $event = makeEvent();
    $preset = EventPositionPreset::create([
        'name' => 'Full Staffing',
        'positions' => ['JAX_CTR', 'MCO_APP'],
    ]);

    $this->put(route('admin.events.update', ['event' => $event->id]), [
        'title' => $event->title,
        'description' => $event->description,
        'start' => $event->start->toDateTimeString(),
        'end' => $event->end->toDateTimeString(),
        'type' => EventType::HOME->value,
        'presetPositions' => $preset->name,
    ])->assertRedirect(route('admin.events.index'));

    expect($event->fresh()->presetPositions)->toBe(['JAX_CTR', 'MCO_APP']);
});

test('assignments cannot be saved onto a registrant from another event', function () {
    $this->actingAs(makeEventsStaff());

    $shownEvent = makeEvent(['title' => 'Shown Event']);
    $otherEvent = makeEvent(['title' => 'Other Event']);

    $foreign = EventPosition::create([
        'event_id' => $otherEvent->id,
        'user_id' => User::factory()->create()->id,
        'requested_position' => 'JAX_CTR',
        'start' => $otherEvent->start,
        'end' => $otherEvent->end,
    ]);

    expect(fn () => Livewire::test(EventRegistrants::class, ['event' => $shownEvent])
        ->set("assignments.{$foreign->id}", [
            'assigned_start' => $otherEvent->start->format('Y-m-d\TH:i'),
            'assigned_end' => $otherEvent->end->format('Y-m-d\TH:i'),
            'assigned_position' => 'JAX_CTR',
        ])
        ->call('save', $foreign->id)
    )->toThrow(ModelNotFoundException::class);

    expect($foreign->fresh()->assigned_position)->toBeNull();
});

test('the assignment form is seeded from the saved assignment', function () {
    $this->actingAs(makeEventsStaff());

    $event = makeEvent();

    $registrant = EventPosition::create([
        'event_id' => $event->id,
        'user_id' => User::factory()->create()->id,
        'requested_position' => 'JAX_CTR',
        'start' => $event->start,
        'end' => $event->end,
        'assigned_start' => $event->start->copy()->addHour(),
        'assigned_end' => $event->end->copy()->addHour(),
        'assigned_position' => 'MCO_APP',
    ]);

    Livewire::test(EventRegistrants::class, ['event' => $event])
        ->assertSet(
            "assignments.{$registrant->id}.assigned_start",
            $event->start->copy()->addHour()->utc()->format('Y-m-d\TH:i')
        );
});
