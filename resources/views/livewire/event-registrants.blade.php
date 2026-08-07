<div>
    @if ($errors->any() && $currentRegistrantId)
        @php
            $user = $registrants->firstWhere('id', $currentRegistrantId)?->user;
        @endphp

        <div role="alert" class="alert alert-error mb-4">
            <div>
            <span class="font-bold">
                Please fix the following for "{{ $user?->first_name }} {{ $user?->last_name }}"
            </span>

                <ul class="list-disc list-inside text-sm mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

                <div class="pt-2">
                    <button
                        type="button"
                        wire:click="dismissErrors"
                        class="btn btn-sm"
                    >
                        Dismiss
                    </button>
                </div>
            </div>
        </div>
    @endif
        @if($success)
            @php
                $user = $registrants->firstWhere('id', $currentRegistrantId)?->user;
            @endphp
            <div role="alert" class="alert alert-success alert-horizontal mt-2 pb-5">
                <span>Position Successfully Saved for {{ $user?->first_name }} {{ $user?->last_name }} </span>
                <div>
                    <button
                        type="button"
                        wire:click="$set('success', false)"
                        class="btn btn-sm"
                    >
                        Dismiss
                    </button>
                </div>
            </div>
        @endif

        @if($showPositionsPublishedAlert)
            <div role="alert" class="alert alert-warning alert-horizontal mt-2">
                <span>Positions Successfully Published</span>
                <div>
                    <button
                        type="button"
                        wire:click="$set('showPositionsPublishedAlert', false)"
                        class="btn btn-sm"
                    >
                        Dismiss
                    </button>
                </div>
            </div>
        @endif
        @if($showUnpublishedPositionsAlert)
            <div role="alert" class="alert alert-warning alert-horizontal mt-2">
                <span>Positions Successfully Unpublished</span>
                <div>
                    <button
                        type="button"
                        wire:click="$set('showUnpublishedPositionsAlert', false)"
                        class="btn btn-sm"
                    >
                        Dismiss
                    </button>
                </div>
            </div>
        @endif
    @unless (sizeof($registrants) == 0)
        <div class="overflow-x-auto">
            <table class='table table-zebra table-md w-max border-2 border-base-300'>
                <thead>
                <tr class='text-xl font-bold'>
                    <th colspan='4'>Registrants</th>
                    <th colspan='5'>
                        @if(!$publishedPositions)
                            <button wire:click="publishPositions" type="button" class="btn btn-primary">
                                Publish Positions
                            </button>
                        @else
                            <button wire:click="unpublishPositions" type="button" class="btn btn-error">
                                Unpublish Positions
                            </button>
                        @endif

                    </th>
                </tr>
                <tr>
                    <th>Name</th>
                    <th>Position Requested</th>
                    <th>Requested Start (UTC)</th>
                    <th>Requested End (UTC)</th>
                    <th>Notes</th>
                    <th>Certifications</th>
                    <th>Time</th>
                    <th>Final Position</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($registrants as $registrant)
                    <tr>
                        <td class='border-r-1 border-base-300'>{{ $registrant->user->first_name }} {{ $registrant->user->last_name }}</td>
                        <td class='border-r-1 border-base-300'>{{ $registrant->requested_position }}</td>
                        <td class='border-r-1 border-base-300'>{{ $registrant->start }}</td>
                        <td class='border-r-1 border-base-300'>{{ $registrant->end }}</td>
                        <td class='border-r-1 border-base-300'>
                            <div tabindex="0" class="collapse bg-base-100 border-base-300 border">
                                <div class="collapse-title font-semibold max-w-xs">Click to Expand</div>
                                <div class="collapse-content text-sm max-w-xs">
                                    {{ $registrant->notes }}
                                </div>
                            </div>
                        </td>
                        <td class='border-r-1 border-base-300'>TODO</td>
                        <td class='border-r-1 border-base-300 max-w-sm'>
                            <div tabindex="0" class="collapse bg-base-100 border-base-300 border">
                                <div class="collapse-title font-semibold">Click to Expand</div>
                                <div class="collapse-content text-sm">
                                    <label class="label">
                                        <span class="label-text text-xs">Assigned Start</span>
                                    </label>
                                    <input type="datetime-local" name="assigned_start" wire:model="assignments.{{ $registrant->id }}.assigned_start"
                                           class="input input-bordered w-full" required>
                                    <label class="label">
                                        <span class="label-text text-xs">Assigned End</span>
                                    </label>
                                    <input type="datetime-local" name="assigned_end"  wire:model="assignments.{{ $registrant->id }}.assigned_end"
                                           class="input input-bordered w-full" required>
                                </div>
                            </div>

                        </td>
                        <td class='border-r-1 border-base-300'>
                            <select name="presetPositions" class="select"  wire:model="assignments.{{ $registrant->id }}.assigned_position">
                                <option disabled selected>Select position</option>
                                <option value="">No position</option>
                                @foreach ($positions as $p)
                                    <option value="{{ $p }}">
                                        {{ str_replace('_', ' ', $p) }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <button wire:click="save({{ $registrant->id }})" type="button" class="btn btn-warning">
                                Save Position
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @else
        <h1>There are no registered controllers.</h1>
    @endunless
</div>
