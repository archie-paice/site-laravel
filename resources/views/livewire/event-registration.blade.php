<!-- Come back and cleanup later -->

<div class="card-body bg-neutral">
    <h2 class="card-title">Request Position</h2>
    <form @if (!$submitted) wire:submit.prevent="store" @else wire:submit.prevent="destroy" @endif
        class="flex flex-col gap-6 w-full px-4">

        <div class="flex gap-6 w-full">

            <div class="flex flex-col gap-6 w-1/2">
                <div class="flex flex-col w-full">
                    <select wire:model="selectedPosition" required class="select select-bordered w-full"
                        @if ($submitted) disabled @endif>
                        <option value=""disabled>Select a position</option>
                        @foreach ($positions as $p)
                            <option value="{{ $p }}">{{ str_replace('_', ' ', $p) }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Pick from selections</p>
                </div>

                <div class="flex flex-col gap-4 w-full">
                    <div class="flex flex-col w-full">
                        <label for="start" class="label-text">Requested Start</label>
                        @if (!$submitted)
                            <input type="datetime-local" wire:model="start" name="start"
                                class="input input-bordered w-full" required
                                @if ($submitted) readonly @endif>
                        @endif
                        @if ($submitted)
                            <p> {{ old('start', $event->start) }} </p>
                        @endif
                    </div>


                    <div class="flex flex-col w-full">
                        <label for="end" class="label-text">Requested End</label>
                        @if (!$submitted)
                            <input type="datetime-local" wire:model="end" name="end"
                                class="input input-bordered w-full" required
                                @if ($submitted) readonly @endif>
                        @endif
                        @if ($submitted)
                            <p> {{ old('end', $event->end) }} </p>
                        @endif
                    </div>

                </div>
            </div>

            <div class="flex flex-col w-1/2 items-end">
                <label for="notes" class="label-text text-right">Additional Notes</label>
                <textarea wire:model="notes"
                    @if ($submitted) placeholder="{{ old('notes', $event->notes) }}" @else placeholder="Eg. Operating on a solo cert" @endif
                    class="textarea textarea-bordered w-full" rows="6" @if ($submitted) readonly @endif></textarea>
            </div>
        </div>
        @if (!$submitted)
            <button class="btn btn-primary" type="submit">Request</button>
        @else
            <button class="btn btn-error" type="submit">Delete Signup</button>
        @endif
    </form>
</div>
