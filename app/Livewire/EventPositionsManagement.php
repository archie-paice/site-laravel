<?php

namespace App\Livewire;

use App\Models\Event;
use App\Models\EventPosition;
use App\Models\EventPositionPreset;
use Livewire\Component;

class EventPositionsManagement extends Component
{
    public Event $event;

    public array $positions = [];

    public string $newPosition = '';

    public array $presetNames = [];

    public string $selectedPreset = '';

    public bool $updated = false;

    public function mount(Event $event)
    {
        $this->event = $event;
        $this->positions = $event->presetPositions ?? [];
        $this->presetNames = EventPositionPreset::orderBy('name')->pluck('name')->all();
    }

    public function addPosition()
    {
        $position = strtoupper(trim($this->newPosition));
        $this->newPosition = '';

        if ($position === '' || $this->hasPosition($position)) {
            return;
        }

        $this->positions[] = $position;
    }

    public function removePosition(string $position)
    {
        $this->positions = collect($this->positions)
            ->reject(fn ($p) => strtoupper($p) === strtoupper($position))
            ->values()
            ->all();
    }

    public function loadPreset()
    {
        if ($this->selectedPreset === '') {
            return;
        }

        $preset = EventPositionPreset::where('name', $this->selectedPreset)->first();

        abort_if($preset === null, 404);

        // In-memory only — nothing is persisted until save().
        $this->positions = collect($preset->positions ?? [])
            ->map(fn ($p) => strtoupper(trim($p)))
            ->filter()
            ->unique()
            ->values()
            ->all();

        $this->selectedPreset = '';
    }

    public function save()
    {
        // Livewire's update endpoint does not inherit the mounting page's route
        // middleware, so mutators have to re-check the permission themselves.
        abort_unless(auth()->user()?->hasPermissionTo('manage events'), 403);

        $newPositions = collect($this->positions)
            ->map(fn ($position) => strtoupper(trim($position)))
            ->filter()
            ->unique()
            ->values();

        $blockedPositions = EventPosition::assignedPositionsOutsideOf($this->event->id, $newPositions->all());

        if ($blockedPositions->isNotEmpty()) {
            $this->addError(
                'positions',
                'Cannot remove assigned position(s): '.$blockedPositions->implode(', ')
            );

            $this->positions = $this->event->fresh()->presetPositions ?? [];
            $this->updated = false;

            return;
        }

        $positions = $newPositions->all();

        $this->event->update(['presetPositions' => $positions]);

        $this->positions = $positions;
        $this->updated = true;

        session()->flash('success', 'Positions updated.');
    }

    public function dismissPositionsError()
    {
        $this->resetErrorBag('positions');
    }

    private function hasPosition(string $position): bool
    {
        return collect($this->positions)->contains(fn ($p) => strtoupper($p) === strtoupper($position));
    }
}
