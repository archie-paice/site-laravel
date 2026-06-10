<div>
    <div class="flex flex-row items-center justify-between mb-2">
        <h2 class="text-lg font-semibold">{{ $scheduleDate->format('l, F j, Y') }}</h2>

        <div class="flex flex-row gap-x-2">
            <button type="button" wire:click="previousDay" class="btn btn-outline btn-sm" title="Previous day">&laquo;</button>
            <button type="button" wire:click="nextDay" class="btn btn-outline btn-sm" title="Next day">&raquo;</button>
        </div>
    </div>

    @unless($bookings->isEmpty())
        @foreach ($bookings as $booking)
            @if (\App\Livewire\ControllerSchedule::canManage($booking))
                <div wire:click="edit({{ $booking->id }})" class="cursor-pointer hover:opacity-80" title="Edit this session">
                    <x-booking-card :booking='$booking'/>
                </div>
            @else
                <x-booking-card :booking='$booking'/>
            @endif
        @endforeach
    @else
        <h1 class="text-lg">No bookings on this date</h1>
    @endunless

    @haspermission('create atc booking')
        <button type="button" class="btn btn-primary btn-sm w-max mt-4" onclick="booking_modal.showModal()">
            Book a Session
        </button>
    @endhaspermission

    {{-- Edit / delete modal --}}
    @if ($editingId)
        <div class="modal modal-open">
            <div class="modal-box">
                <h3 class="text-xl font-bold mb-4">Edit Session</h3>

                <div class="flex flex-col gap-y-3">
                    <label class="flex flex-col">
                        <span class="label-text font-semibold mb-1">Position</span>
                        <input type="text" wire:model="editPosition" class="input input-bordered w-full">
                        @error('editPosition') <span class="text-error text-sm">{{ $message }}</span> @enderror
                    </label>

                    <div class="grid grid-cols-2 gap-4">
                        <label class="flex flex-col">
                            <span class="label-text font-semibold mb-1">Start (zulu)</span>
                            <input type="datetime-local" wire:model="editStart" class="input input-bordered w-full">
                            @error('editStart') <span class="text-error text-sm">{{ $message }}</span> @enderror
                        </label>

                        <label class="flex flex-col">
                            <span class="label-text font-semibold mb-1">End (zulu)</span>
                            <input type="datetime-local" wire:model="editEnd" class="input input-bordered w-full">
                            @error('editEnd') <span class="text-error text-sm">{{ $message }}</span> @enderror
                        </label>
                    </div>

                    <label class="flex flex-col">
                        <span class="label-text font-semibold mb-1">Description (optional)</span>
                        <textarea wire:model="editDescription" rows="3" class="textarea textarea-bordered w-full resize-none"></textarea>
                        @error('editDescription') <span class="text-error text-sm">{{ $message }}</span> @enderror
                    </label>
                </div>

                <div class="modal-action justify-between">
                    <button type="button" wire:click="delete" wire:confirm="Delete this session? This cannot be undone."
                            class="btn btn-error">Delete</button>

                    <div class="flex gap-x-2">
                        <button type="button" wire:click="update" class="btn btn-primary">Save</button>
                        <button type="button" wire:click="cancelEdit" class="btn btn-ghost">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
