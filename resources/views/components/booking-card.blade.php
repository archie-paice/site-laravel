<div class='card border-2 border-base-300 mt-2'>
    <div class='p-4 flex flex-col'>
        <strong class='text-xl'>{{ $booking->position }}</strong>

        <p class='text-lg'>{{ $booking->user->name }}</p>

        @if ($booking->description)
            <p class='text-base opacity-70'>{{ $booking->description }}</p>
        @endif

        <h2 class='text-sm opacity-70'>{{ $booking->start->format('Hi') }}z - {{ $booking->end->format('Hi') }}z</h2>
    </div>
</div>
