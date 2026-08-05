@extends(Auth::user()->hasRole('training') ? 'layouts.admin' : 'layouts.main')

@section('title', 'Training Ticket - #'.$trainingTicket->id)

@section('body')
    <a href="{{ Auth::user()->hasRole('training') ? route('training-tickets.index') : route('users.show.training-tickets', $trainingTicket->user_id) }}" class="btn btn-ghost mb-4">&larr; Back</a>
    <div class="card card-body bg-base-300 w-full max-w-3xl">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8">
            <x-label label="Session Date" :value="$trainingTicket->session_start"/>
            <x-label label="Session Duration" :value="$trainingTicket->duration"/>
            <x-label label="Student" :value="$trainingTicket->student->first_name.' '.$trainingTicket->student->last_name"/>
            <x-label label="Instructor" :value="$trainingTicket->instructor->first_name.' '.$trainingTicket->instructor->last_name"/>
            <x-label label="Position" :value="$trainingTicket->position"/>
            <x-label-slot label="Score">
                <x-rating-readonly :rating="$trainingTicket->score"/>
            </x-label-slot>

            @if($trainingTicket->issuedCertificationLevel)
                <x-label-slot label="Certification Pushed">
                    <span class="badge badge-success badge-lg">
                        {{ $trainingTicket->issuedCertificationLevel->facility?->identifier }}
                        {{ $trainingTicket->issuedCertificationLevel->name }}
                        ({{ $trainingTicket->issuedCertificationLevel->abbreviation }})
                    </span>
                </x-label-slot>
            @endif
        </div>
        <x-label-slot label="Notes">
            <div id="notes" class='bg-white p-2 rounded-md min-h-50 w-full'>{!! $trainingTicket->notes !!}</div>
        </x-label-slot>
    </div>

    <div class="mt-6">
        <x-label-slot label="Notes">
            <div class="bg-base-100 border border-base-300 rounded-md p-4 min-h-40 prose max-w-none">
                {!! $trainingTicket->notes !!}
            </div>
        </x-label-slot>
    </div>
@endsection
