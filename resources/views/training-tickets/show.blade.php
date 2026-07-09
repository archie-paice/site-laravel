@extends('layouts.admin')

@section('title', 'Training Ticket - #'.$trainingTicket->id)

@section('body')
    <x-card-component>
        <div class="grid md:grid-cols-2 grid-cols-1 gap-x-10 gap-y-3 mt-4">
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

        <div class="mt-6">
            <x-label-slot label="Notes">
                <div class="bg-base-100 border border-base-300 rounded-md p-4 min-h-40 prose max-w-none">
                    {!! $trainingTicket->notes !!}
                </div>
            </x-label-slot>
        </div>
    </x-card-component>
@endsection
