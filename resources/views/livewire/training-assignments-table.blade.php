<div>
    <div class="flex flex-row gap-x-2 items-center mb-2">
        <label for="showInactive">Show Inactive Requests</label>
        <input
            wire:model="includeInactive"
            wire:click="updateAssignments"
            name="showInactive"
            type="checkbox"
        />
    </div>
    @unless(sizeof($trainingAssignments) == 0)
        <table class='table table-zebra table-md w-max border-2 border-base-300'>
            <thead>
            <tr>
                <th>CID</th>
                <th>Name</th>
                <th>Instructor</th>
                <th>Training Requested</th>
                <th>Requested At</th>
                <th>Last Session</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($trainingAssignments as $trainingAssignment)
                <tr>
                    <td>
                        <a href="{{route('users.show', ['user' => $trainingAssignment->trainee_id])}}">
                            {{$trainingAssignment->trainee_id}}
                        </a>
                    </td>
                    <td>
                        <a href="{{route('users.show', ['user' => $trainingAssignment->trainee_id])}}">
                            {{$trainingAssignment->trainee->first_name.' '.$trainingAssignment->trainee->last_name}}
                        </a>
                    </td>

                    @if(is_null($trainingAssignment->instructor))
                        <td>None</td>
                    @else
                        <td>
                            <a href="{{route('users.show', ['user' => $trainingAssignment->instructor_id])}}">
                                {{$trainingAssignment->instructor->first_name.' '.$trainingAssignment->instructor->last_name}}
                            </a>
                        </td>
                    @endif

                    <td>{{$trainingAssignment->training_type}}</td>
                    <td>{{(new DateTime($trainingAssignment->created_at))->format("m-d-y, h:m A")}}</td>
                    <td>NOT IMPL. YET</td>

                    @if ($trainingAssignment->active)
                        <td class="text-success">Active</td>
                    @else
                        <td class="text-error">Inactive</td>
                    @endif
                    <td>
                        <ul class='text-accent menu menu-horizontal h-10 items-center gap-x-5 justify-center'>
                            <li>
                                <details>
                                    <summary>Actions</summary>
                                    <ul class="bg-base-100 text-base-content rounded-t-none p-2 z-10">
                                        @haspermission('claim students')
                                            @if (is_null($trainingAssignment->instructor))
                                                <li>
                                                    <form
                                                        action="{{route("training-assignments.claim", ["assignment" => $trainingAssignment->id])}}"
                                                        method="POST"
                                                    >
                                                        @method('PUT')
                                                        @csrf
                                                        <button type="submit">Claim Student</button>
                                                    </form>
                                                </li>
                                            @endif
                                        @endhaspermission

                                        @if($trainingAssignment->instructor_id == Auth::user()->id)
                                            <li>
                                                <form
                                                    action="{{route("training-assignments.drop", ["assignment" => $trainingAssignment->id])}}"
                                                    method="POST"
                                                >
                                                    @method('PUT')
                                                    @csrf
                                                    <button type="submit">Drop Student</button>
                                                </form>
                                            </li>
                                        @endif

                                        @haspermission('manage students')
                                            <li><a href={{ route('training-assignments.edit', ['assignment' => $trainingAssignment->id]) }}>Edit</a></li>
                                        @endhaspermission
                                    </ul>
                                </details>
                            </li>
                        </ul>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <h1>There are no training assignments.</h1>
    @endunless

    <div class="w-150 mt-5">
        {{ $trainingAssignments->links() }}
    </div>
</div>
