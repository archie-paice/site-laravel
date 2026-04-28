@if(auth()->id() == $userId)
    @if($registeredEvents->isEmpty() || ! $registeredEvents->first()->active)
        <div>
            <strong>You don't have an active event signup. Check the events calendar to see what's upcoming.</strong>
        </div>
    @endif
@endif

<table class='table table-zebra table-md w-max mt-5'>
    <thead>
    <tr>
        <th>Event</th>
        <th>Position Requested</th>
        <th>Time Requested</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>
    @unless(sizeof($registeredEvents) == 0)
        @foreach ($registeredEvents as $registration)
            <tr>

                <td>{{ $registration->title }}</td>

                <td>
                    {{ $registration->pivot->requested_position}}
                </td>


                <td>{{ $registration->getFormattedRangeAttribute()}}</td>
                <td>{{ $registration->pivot->position_status }}</td>
            </tr>
        @endforeach
    @endunless
    </tbody>
</table>

