<?php

namespace App\Livewire;

use App\Models\Event;
use App\Models\EventPosition;
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
        $newPositions = collect(explode(',', $this->positions))
            ->map(fn ($position) => strtoupper(trim($position)))
            ->filter()
            ->unique()
            ->values();

        $oldPositions = collect($this->event->presetPositions ?? []);
        $removedPositions = $oldPositions->diff($newPositions);

        $assignedRemovedPositions = EventPosition::where('event_id', $this->event->id)
            ->whereIn('assigned_position', $removedPositions)
            ->whereNotNull('assigned_position')
            ->pluck('assigned_position')
            ->unique()
            ->values();

        if ($assignedRemovedPositions->isNotEmpty()) {
            $this->addError(
                'positions',
                'Cannot remove assigned position(s): '.$assignedRemovedPositions->implode(', ')
            );

            $this->positions = $oldPositions->implode(', ');

            $this->updated = false;

            return;
        }

        $positions = $newPositions->toArray();

        $this->event->update([
            'presetPositions' => $positions,
        ]);

        $this->positions = implode(', ', $positions);

        $this->updated = true;

        session()->flash('success', 'Positions updated.');
    }

    public function dismissPositionsError()
    {
        $this->resetErrorBag('positions');
    }
}
