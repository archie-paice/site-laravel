<form wire:submit.prevent="save">
    <div class="collapse bg-base-100 border border-base-300">
        <input type="radio" name="my-accordion-1" checked="checked" />

        <div class="collapse-title font-semibold">
            Positions
        </div>

        <div class="collapse-content text-sm">
            <textarea
                wire:model.defer="positions"
                class="textarea textarea-bordered w-full"
                rows="4"
                placeholder="JAX_CTR, MCO_GND, MCO_TWR"
            ></textarea>

            <button type="submit" class="btn btn-primary mt-4">
                Save Positions
            </button>

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

            @if($updated)
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
</form>
