<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\EventPosition;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class EventRegistration extends Component
{
    // The data will be position and notes
    // IDEA FOR LATER: Color code system on the events team side to show if a position has been taken or not. Also stats to see the
    // most wanted position?

    // LATER: Come back and clean up code

    // --------Functionality------------
    // Each event will pass itself to this component. Then,
    // get the position presets for the event that is being registered for.
    // This will be passed to the view. That way, the user can select their position
    // from the list.

    public $notes;
    public Event $event;
    public $positionPreset;
    public $positions = [];
    public $selectedPosition;
    public $start;
    public $end;
    public $submitted = false;

    public function mount(Event $event)
    {
        $authenticatedUser = Auth::user();
        $this->event = $event;
        $this->positions = $event->presetPositions ?? [];
        $this->selectedPosition = '';

        $registration = EventPosition::where('user_id', $authenticatedUser->id)->where('event_id', $event->id)->first();

        if ($registration) {
            $this->selectedPosition = $registration->requested_position;
            $this->start = $registration->start;
            $this->end = $registration->end;
            $this->notes = $registration->notes;
            $this->submitted = true;
        }
    }

    public function store()
    {
        $authenticatedUser = Auth::user();

        $validated = $this->validate([
            'selectedPosition' => 'required|string',
            'notes' => 'nullable|string|max:500',
            'start' => [
                'required',
                'date',
                'after_or_equal:' . $this->event->start->toDateTimeString(),
                'before_or_equal:' . $this->event->end->toDateTimeString(),
            ],
            'end' => [
                'required',
                'date',
                'after:start',
                'before_or_equal:' . $this->event->end->toDateTimeString(),
            ],
        ]);

        EventPosition::create([
            'user_id' => $authenticatedUser->id,
            'event_id' => $this->event->id,
            'requested_position' => $validated['selectedPosition'],
            'start' => $validated['start'],
            'end' => $validated['end'],
            'notes' => $validated['notes'],
            'requested_position' => $this->selectedPosition,
            'start' => $this->start,
            'end' => $this->end,
            'notes' => $this->notes,
        ]);

        $this->submitted = true;
    }

    public function destroy() {
        $authenticatedUser = Auth::user();
        $registration = EventPosition::where('user_id', $authenticatedUser->id)->where('event_id', $this->event->id)->first();
        $registration->delete();
        $this->submitted = false;
    }
    public function render()
    {
        return view('livewire.event-registration');
    }
}
