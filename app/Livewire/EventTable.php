<?php

namespace App\Livewire;

use App\Models\Event;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class EventTable extends Component
{
    private Collection $events;

    public function mount() {
        $this->events = Event::all();
    }

    public function render()
    {
        return view('livewire.event-table', ['events' => $this->events]);
    }
}
