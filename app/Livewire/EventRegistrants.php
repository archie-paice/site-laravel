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

    public array $assignments = [];

    public ?int $currentRegistrantId = null;

    // Alerts
    public bool $success = false;

    public bool $showPositionsPublishedAlert = false;

    public bool $showUnpublishedPositionsAlert = false;

    public bool $publishedPositions = false;

    public function mount(Event $event)
    {
        $this->event = $event;
        $this->registrants = EventPosition::with('user')->where('event_id', $event->id)->get();
        $this->positions = $event->presetPositions ?? [];

        $this->publishedPositions = (bool) $this->event->published;

        foreach ($this->registrants as $registrant) {
            $this->assignments[$registrant->id] = [
                'assigned_start' => ($registrant->assigned_start ?? $registrant->start)->utc()->format('Y-m-d\TH:i'),
                'assigned_end' => ($registrant->assigned_end ?? $registrant->end)->utc()->format('Y-m-d\TH:i'),
                'assigned_position' => $registrant->assigned_position,
            ];
        }
    }

    public function dismissErrors()
    {
        $this->resetValidation();
        $this->currentRegistrantId = null;
    }

    /**
     * Livewire's update endpoint does not inherit the mounting page's route
     * middleware, so every mutator below has to re-check its permission itself.
     */
    private function authorizePermission(string $permission): void
    {
        abort_unless(auth()->user()?->hasPermissionTo($permission), 403);
    }

    public function publishPositions()
    {
        $this->authorizePermission('publish events');

        $this->event->published = true;
        $this->event->save();

        $this->publishedPositions = $this->event->published;

        $this->showPositionsPublishedAlert = true;
    }

    public function unpublishPositions()
    {
        $this->authorizePermission('publish events');

        $this->event->published = false;
        $this->event->save();
        $this->publishedPositions = $this->event->published;
        $this->showUnpublishedPositionsAlert = true;
    }

    public function save($id)
    {
        $this->authorizePermission('assign event positions');

        $this->currentRegistrantId = $id;

        $this->validate([
            "assignments.$id.assigned_start" => ['required', 'date'],
            "assignments.$id.assigned_end" => ['required', 'date', "after:assignments.$id.assigned_start"],
            "assignments.$id.assigned_position" => ['required', 'string'],
        ], [
            "assignments.$id.assigned_start.required" => 'Assigned start is required.',
            "assignments.$id.assigned_start.date" => 'Assigned start must be a valid date.',

            "assignments.$id.assigned_end.required" => 'Assigned end is required.',
            "assignments.$id.assigned_end.date" => 'Assigned end must be a valid date.',
            "assignments.$id.assigned_end.after" => 'Assigned end must be after assigned start.',

            "assignments.$id.assigned_position.required" => 'Assigned position is required.',
        ]);

        $data = $this->assignments[$id] ?? [];

        if (! $data) {
            return;
        }

        // $id arrives from the client, so scope the lookup to this event rather
        // than letting one event's manage page write another event's assignments.
        $registrant = EventPosition::where('event_id', $this->event->id)->findOrFail($id);

        $registrant->assigned_start = $data['assigned_start'] ?? null;
        $registrant->assigned_end = $data['assigned_end'] ?? null;
        $registrant->assigned_position = $data['assigned_position'] ?? null;
        $registrant->position_status = 'assigned';

        $registrant->save();

        $this->success = true;
    }

    public function render()
    {
        return view('livewire.event-registrants');
    }
}
