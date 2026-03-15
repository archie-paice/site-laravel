<?php

namespace App\Livewire;

use Asantibanez\LivewireCalendar\LivewireCalendar;
use Livewire\Component;
use Illuminate\Support\Collection;
use App\Models\Event;

class EventsCalendar extends LivewireCalendar
{
    public function events() : Collection
    {
        return Event::select('id', 'title', 'description', 'start as date')->get();
    }

    public function onEventClick($eventId)
    {
        $event = Event::find($eventId);
        return redirect()->route('events.show', $eventId);
    }
}
