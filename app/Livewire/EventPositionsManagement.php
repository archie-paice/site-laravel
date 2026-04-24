<?php

namespace App\Livewire;

use App\Models\Event;
use App\Models\EventPosition;
use Illuminate\Support\Collection;
use Livewire\Component;

class EventPositionsManagement extends Component
{
    public Event $event;

    public string $positions = '';

    public bool $updated = false;

    public function mount(Event $event)
    {
        $this->event = $event;

        $this->positions = implode(', ', $event->presetPositions ?? []);
    }

    public function save()
    {
        $positions = collect(explode(',', $this->positions))
            ->map(fn ($position) => strtoupper(trim($position)))
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        $this->event->update([
            'presetPositions' => $positions,
        ]);

        $this->positions = implode(', ', $positions);

        $this->updated = true;

        session()->flash('success', 'Positions updated.');
    }
}
