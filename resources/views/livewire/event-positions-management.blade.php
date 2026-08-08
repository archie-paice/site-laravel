<div class="card bg-base-100 border border-base-300 w-full">
    <div class="card-body">
        <h2 class="card-title">Positions</h2>

        @if ($readOnly)
            <div class="flex flex-wrap gap-2">
                @forelse ($positions as $position)
                    <span class="badge badge-outline">{{ $position }}</span>
                @empty
                    <span class="text-sm opacity-60">No positions added yet.</span>
                @endforelse
            </div>
        @else
            <div class="flex flex-wrap items-end gap-3">
                <div class="flex-1 min-w-48">
                    <label class="label">Load from a preset</label>
                    <select wire:model="selectedPreset" class="select select-bordered w-full">
                        <option value="">Choose a preset&hellip;</option>
                        @foreach ($presetNames as $preset)
                            <option value="{{ $preset }}">{{ $preset }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="button" wire:click="loadPreset" class="btn btn-secondary">
                    Load Preset
                </button>
            </div>

            <p class="text-xs opacity-70">
                Loading a preset replaces the list below. Nothing is saved until you click Save Positions.
            </p>

            <div class="divider"></div>

            <form wire:submit.prevent="save">
                <x-list-editor
                    :items="$positions"
                    add-action="addPosition"
                    remove-action="removePosition"
                    item-model="newPosition"
                    placeholder="JAX_CTR"
                />

                <button type="submit" class="btn btn-primary mt-4">
                    Save Positions
                </button>
            </form>
        @endif

        @error('positions')
            <div role="alert" class="alert alert-error alert-horizontal mt-2">
                <span>{{ $message }}</span>

                <div>
                    <button
                        type="button"
                        wire:click="dismissPositionsError"
                        class="btn btn-sm"
                    >
                        Dismiss
                    </button>
                </div>
            </div>
        @enderror

        @if ($updated)
            <div role="alert" class="alert alert-warning alert-horizontal mt-2">
                <span>Positions Successfully Updated</span>
                <div>
                    <button
                        type="button"
                        wire:click="$set('updated', false)"
                        class="btn btn-sm"
                    >
                        Dismiss
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>
