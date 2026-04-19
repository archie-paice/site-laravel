<?php

namespace App\Livewire;
use App\Models\Event;
use Illuminate\Database\Eloquent\Collection;

use Livewire\Component;

class EventRegistrants extends Component
{
    private Collection $registrants;

    public function mount() {
        $this->registrants = EventRegistrants::all();
    }

    public function render()
    {
        return view('livewire.event-registrants', ['registrants' => $this->registrants]);
    }
}
