<?php

use App\Enums\EventType;
use App\Livewire\EventsCalendar;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\PermissionSeeder;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(PermissionSeeder::class);
});

function makeEventManager(): User
{
    $user = User::factory()->create();
    $user->assignRole('staff', 'events');

    return $user;
}

test('updating an event persists the new title', function () {
    $this->actingAs(makeEventManager());

    $event = Event::create([
        'title' => 'Original Title',
        'description' => 'Original description',
        'start' => now()->addDay(),
        'end' => now()->addDay()->addHours(2),
        'type' => EventType::HOME,
        'featured_fields' => [],
        'hidden' => false,
    ]);

    $response = $this->put(route('admin.events.update', ['event' => $event->id]), [
        'title' => 'Updated Title',
        'description' => 'Updated description',
        'start' => now()->addDay()->toDateTimeString(),
        'end' => now()->addDay()->addHours(2)->toDateTimeString(),
        'type' => EventType::HOME->value,
    ]);

    $response->assertRedirect(route('admin.events.index'));
    expect($event->fresh()->title)->toBe('Updated Title');
});

test('admin events list renders event titles', function () {
    $this->actingAs(makeEventManager());

    Event::create([
        'title' => 'Visible In Admin List',
        'description' => 'desc',
        'start' => now()->addDay(),
        'end' => now()->addDay()->addHours(2),
        'type' => EventType::HOME,
        'featured_fields' => [],
        'hidden' => false,
    ]);

    $this->get(route('admin.events.index'))
        ->assertOk()
        ->assertSee('Visible In Admin List');
});

test('public calendar hides hidden events', function () {
    $mid = Carbon::create(now()->year, now()->month, 15, 12);

    Event::create([
        'title' => 'Published Event',
        'description' => 'desc',
        'start' => $mid,
        'end' => $mid->copy()->addHours(2),
        'type' => EventType::HOME,
        'featured_fields' => [],
        'hidden' => false,
    ]);

    Event::create([
        'title' => 'Secret Event',
        'description' => 'desc',
        'start' => $mid,
        'end' => $mid->copy()->addHours(2),
        'type' => EventType::HOME,
        'featured_fields' => [],
        'hidden' => true,
    ]);

    Livewire::test(EventsCalendar::class)
        ->assertSee('Published Event')
        ->assertDontSee('Secret Event');
});

test('calendar shows events on visible adjacent-month days', function () {
    // April 2026 grid begins on Sun Mar 29, so it renders trailing March days.
    $gridStart = Carbon::create(2026, 4, 1)->startOfWeek(Carbon::SUNDAY);
    expect($gridStart->month)->toBe(3); // sanity: it really is an adjacent-month day

    Event::create([
        'title' => 'Adjacent Month Event',
        'description' => 'desc',
        'start' => $gridStart->copy()->addHours(12),
        'end' => $gridStart->copy()->addHours(14),
        'type' => EventType::HOME,
        'featured_fields' => [],
        'hidden' => false,
    ]);

    Livewire::test(EventsCalendar::class)
        ->set('currentYear', 2026)
        ->set('currentMonth', 4)
        ->assertSee('Adjacent Month Event');
});
