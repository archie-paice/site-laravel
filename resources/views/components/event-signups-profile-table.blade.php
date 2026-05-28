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
        <th>Final Assignment</th>
        <th>Final Start</th>
        <th>Final End</th>
    </tr>
    </thead>
    <tbody>
    @unless(sizeof($registeredEvents) == 0)
        @foreach ($registeredEvents as $registration)

            <tr>
                @if($registration->published)
                    <td>
                        <a href="{{ route('events.show', $registration) }}" class="link link-primary">
                            {{ $registration->title }}
                        </a>
                    </td>

                <td>
                    {{ $registration->pivot->requested_position}}
                </td>

                <td>{{ $registration->getFormattedRangeAttribute()}}</td>
                <td>{{ $registration->pivot->assigned_position}}</td>
                    <td>{{ $registration->pivot->assigned_start}}</td>
                    <td>{{ $registration->pivot->assigned_end}}</td>
                @else
                    <td>
                        <a href="{{ route('events.show', $registration) }}" class="link link-primary">
                            {{ $registration->title }}
                        </a>
                    </td>
                    <td>
                        {{ $registration->pivot->requested_position}}
                    </td>
                    <td>{{ $registration->getFormattedRangeAttribute()}}</td>
                    <td>Position not yet published</td>
                @endif
            </tr>

        @endforeach
    @endunless
    </tbody>
</table>

