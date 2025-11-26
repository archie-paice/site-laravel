<table class="table table-zebra">
    <thead>
    <tr>
        <th>Instructor</th>
        <th>Position</th>
        <th>Progress Rating</th>
        <th>Session Date</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    @if(count($trainingTickets) == 0)
        <tr>
            <td colspan="5" class="text-xl">No Training Data to Display</td>
        </tr>
    @endif
    @foreach($trainingTickets as $trainingTicket)
        <tr>
            <td>
                <a href="{{route('users.show', ['user' => $trainingTicket->instructor->id])}}">
                    {{$trainingTicket->instructor->first_name.' '.$trainingTicket->instructor->last_name}}
                </a>
            </td>
            <td>{{$trainingTicket->position}}</td>
            <td>
                <x-rating-readonly :rating="$trainingTicket->score"/>
            </td>
            <td>{{$trainingTicket->session_start}}</td>
            <td>
                @if ($trainingTicket->vatusa_synced)
                    <h2 class="badge badge-success">VATUSA Synced</h2>
                @else
                    <h2 class="badge badge-warning">Pending VATUSA Sync</h2>
                @endif
            </td>
            <td>
                <a href="{{route('training-tickets.show', ['ticket' => $trainingTicket->id])}}">
                    View
                </a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
