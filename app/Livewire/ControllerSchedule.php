<?php

namespace App\Livewire;

use App\Models\AtcBooking;
use Carbon\Carbon;
use Livewire\Component;

class ControllerSchedule extends Component
{
    public string $date;

    public ?int $editingId = null;

    public string $editPosition = '';

    public string $editStart = '';

    public string $editEnd = '';

    public ?string $editDescription = null;

    public function mount() {
        $this->date = now()->toDateString();
    }

    public function previousDay() {
        $this->date = Carbon::parse($this->date)->subDay()->toDateString();
    }

    public function nextDay() {
        $this->date = Carbon::parse($this->date)->addDay()->toDateString();
    }

    public static function canManage(AtcBooking $booking): bool {
        $user = auth()->user();

        return !is_null($user) && ($user->id === $booking->user_id || $user->hasRole('staff'));
    }

    public function edit(int $bookingId) {
        $booking = AtcBooking::findOrFail($bookingId);
        abort_unless(self::canManage($booking), 403);

        $this->editingId = $booking->id;
        $this->editPosition = $booking->position;
        $this->editStart = $booking->start->format('Y-m-d\TH:i');
        $this->editEnd = $booking->end->format('Y-m-d\TH:i');
        $this->editDescription = $booking->description;
        $this->resetValidation();
    }

    public function cancelEdit() {
        $this->editingId = null;
    }

    public function update() {
        $booking = AtcBooking::findOrFail($this->editingId);
        abort_unless(self::canManage($booking), 403);

        $validated = $this->validate([
            'editPosition' => ['required', 'string', 'max:255'],
            'editStart' => ['required', 'date'],
            'editEnd' => ['required', 'date', 'after:editStart'],
            'editDescription' => ['nullable', 'string', 'max:1000'],
        ]);

        $booking->update([
            'position' => strtoupper($validated['editPosition']),
            'start' => $validated['editStart'],
            'end' => $validated['editEnd'],
            'description' => $validated['editDescription'],
        ]);

        $this->editingId = null;
    }

    public function delete() {
        $booking = AtcBooking::findOrFail($this->editingId);
        abort_unless(self::canManage($booking), 403);

        $booking->delete();

        $this->editingId = null;
    }

    public function render() {
        return view('livewire.controller-schedule', [
            'scheduleDate' => Carbon::parse($this->date),
            'bookings' => AtcBooking::with('user')
                ->whereDate('start', $this->date)
                ->orderBy('start')
                ->get(),
        ]);
    }
}
