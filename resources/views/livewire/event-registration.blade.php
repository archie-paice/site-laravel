<!-- Come back and cleanup later -->

<div class="card-body bg-neutral">
    <h2 class="card-title">Request Position</h2>

    <form
        @if (!$submitted) wire:submit.prevent="store" @else wire:submit.prevent="destroy" @endif
    class="flex flex-col gap-6 w-full px-4"
    >

        @if ($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <div class="flex gap-6 w-full">

            <div class="flex flex-col gap-6 w-1/2">

                <div class="flex flex-col w-full">
                    <select
                        wire:model.live="selectedPosition"
                        class="select select-bordered w-full"
                        @if ($submitted) disabled @endif
                    >
                        <option value="" disabled>Select a position</option>

                        @foreach ($positions as $p)
                            <option value="{{ $p }}">
                                {{ str_replace('_', ' ', $p) }}
                            </option>
                        @endforeach
                    </select>

                    @error('selectedPosition')
                    <p class="text-error text-sm mt-1">{{ $message }}</p>
                    @enderror

                    <p class="text-xs text-gray-500 mt-1">
                        Pick from selections
                    </p>
                </div>


                <div class="flex flex-col gap-4 w-full">

                    <div class="flex flex-col w-full">
                        <label for="start" class="label-text">
                            Requested Start
                        </label>

                        @if (!$submitted)

                            <input
                                type="datetime-local"
                                wire:model.live="start"
                                name="start"
                                class="input input-bordered w-full"
                            >

                            @error('start')
                            <p class="text-error text-sm mt-1">
                                {{ $message }}
                            </p>
                            @enderror

                        @else

                            <p>{{ $start }}</p>

                        @endif
                    </div>



                    <div class="flex flex-col w-full">

                        <label for="end" class="label-text">
                            Requested End
                        </label>

                        @if (!$submitted)

                            <input
                                type="datetime-local"
                                wire:model.live="end"
                                name="end"
                                class="input input-bordered w-full"
                            >

                            @error('end')
                            <p class="text-error text-sm mt-1">
                                {{ $message }}
                            </p>
                            @enderror

                        @else

                            <p>{{ $end }}</p>

                        @endif

                    </div>

                </div>

            </div>


            <div class="flex flex-col w-1/2 items-end">

                <label for="notes" class="label-text text-right">
                    Additional Notes
                </label>


                <textarea
                    wire:model.live="notes"
                    class="textarea textarea-bordered w-full"
                    rows="6"
                    @if ($submitted)
                        readonly
                    placeholder="{{ $notes }}"
                    @else
                        placeholder="Eg. Operating on a solo cert"
                    @endif
                ></textarea>


                @error('notes')
                <p class="text-error text-sm mt-1">
                    {{ $message }}
                </p>
                @enderror

            </div>

        </div>


        @if (!$submitted)

            <button class="btn btn-primary" type="submit">
                Request
            </button>

        @else

            <button class="btn btn-error" type="submit">
                Delete Signup
            </button>

        @endif

    </form>
</div>
