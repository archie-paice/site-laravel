<?php

namespace App\Livewire;
use App\Models\Event;
use App\Models\EventPosition;
use Illuminate\Database\Eloquent\Collection;

use Livewire\Component;

class EventRegistrants extends Component
{
    public Collection $registrants;
    public Event $event;
    public array $positions = [];

    public function mount(Event $event) {
        $this->event = $event;
        $this->registrants = EventPosition::with('user')->where('event_id', $event->id)->get();
        $this->positions = $event->presetPositions ?? [];
    }

    public function render()
    {
        return view('livewire.event-registrants');
    }
}
