<div>
    @unless (sizeof($events) == 0)
        <table x-data='$events' class='table table-zebra table-md w-max border-2 border-base-300'>
            <thead>
                <tr class='text-xl font-bold'>
                    <th colspan='4'>Events</th>
                    <th colspan='8'>
                    </th>
                </tr>
                <tr colspan='4'>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Start (GMT)</th>
                    <th>End (GMT)</th>
                    <th>Hidden</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($events as $event)
                    <tr>
                        <td class='border-r-1 border-base-300'>
                            <a href={{ route('manage-events.show', ['event' => $event->id]) }}
                                class='text-base-content no-underline'>{{ $event->name }}</a>
                        </td>
                        <td class='border-r-1 border-base-300'>{{ $event->type }}</td>
                        <td class='border-r-1 border-base-300'>{{ $event->start }}</td>
                        <td class='border-r-1 border-base-300'>{{ $event->end }}</td>
                        <td class='border-r-1 border-base-300'>{{ $event->hidden ? 'Yes' : 'No' }}</td>
                        <td class='border-r-1 border-base-300'>
                            <button class="btn btn-primary">Manage</button>
                            <a href="{{ route('manage-events.edit', ['event' => $event->id]) }}" class="btn btn-accent">
                                Edit
                            </a>
                            <form action="{{ route('manage-events.destroy', ['event' => $event->id]) }}" method="POST"
                                class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-error"
                                    onclick="return confirm('Are you sure you want to delete this event?')">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <h1>There are no created events.</h1>
    @endunless
</div>
