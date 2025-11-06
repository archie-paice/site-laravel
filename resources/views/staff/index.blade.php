@extends('layouts.main')

@section('title', 'ARTCC Staff')

@section('body')
    <div class="w-full grid grid-cols-3 gap-10">
        <x-staff-card
            position="Air Traffic Manager"
            description="The Air Traffic Manager oversees day-to-day operations and collaborates with all staff members of the ARTCC."
            :user="$atm"
            reportsTo="VATUSA"
        />

        <x-staff-card
            position="Deputy Air Traffic Manager"
            description="The Deputy Air Traffic Manager assists the Air Traffic Manager in overseeing the ARTCC. The Deputy Air Traffic Manager also oversees the Web, Events, and Facilities Department and assists as needed."
            :user="$datm"
            reportsTo="Air Traffic Manager"
        />

        <x-card-component title="Training Administrator">
            <h2 class="text-2xl mb-5">{{ $ta->first_name.' '.$ta->last_name }}</h2>

            <p class="text-lg">The Training Administrator is responsible for overseeing the training department of the ARTCC.</p>
            <p class="text-lg"><strong>Reports to:</strong> Air Traffic Manager</p>
        </x-card-component>
    </div>
@endsection
