    <table class='table table-zebra table-md w-max mt-5'>
        <thead>
        <tr>
            <th>Instructor</th>
            <th>Training Requested</th>
            <th>Requested At</th>
            <th>Last Session</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
            @unless(sizeof($trainingAssignments) == 0)
                @foreach ($trainingAssignments as $trainingAssignment)
                    <tr>
                        @if(is_null($trainingAssignment->instructor))
                            <td>None</td>
                        @else
                            <td>
                                <a href="{{route('users.show', ['user' => $trainingAssignment->instructor_id])}}">
                                    {{$trainingAssignment->instructor->name}}
                                </a>
                            </td>
                        @endif

                        <td>{{$trainingAssignment->training_type->mapToString()}}</td>
                        <td>{{(new DateTime($trainingAssignment->created_at))->format("m-d-y, h:m A")}}</td>
                        <td>
                            @if(count($trainingAssignment->student->trainingTicketsAsStudent) == 0)
                                None Logged
                            @else
                                {{(new DateTime($trainingAssignment->student->trainingTicketsAsStudent->first()->session_start))->format("m-d-y, h:m A")}}
                            @endif
                        </td>

                        <td>
                            <x-training-assignment-status-label :status="$trainingAssignment->status"/>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5" class="text-center">
                        No training assignments found.
                    </td>
                </tr>
            @endunless
        </tbody>
    </table>

{{ $trainingAssignments->links() }}