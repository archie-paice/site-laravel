@extends('layouts.event-manage')

@section('event-content')
    <div class="w-full lg:w-1/2 mx-auto">
        @if ($positionsAccess === 'readonly')
            <div role="alert" class="alert alert-warning mb-4">
                <i class="fa-solid fa-lock"></i>
                <span>
                    You can view this event's positions but cannot edit them
                    (missing the <code>assign event positions</code> permission).
                </span>
            </div>
        @endif

        @livewire('event-positions-management', ['event' => $event, 'readOnly' => $positionsAccess === 'readonly'])
    </div>
@endsection
