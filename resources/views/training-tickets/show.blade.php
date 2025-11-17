@extends('layouts.admin')

@section('title', 'Training Ticket - #'.$trainingTicket->id)

@section('body')
    <div class="card card-body bg-base-300 w-max">
        @if($trainingTicket->instructor_id == Auth::user()->id || Auth::user()->hasPermissionTo('manage training tickets'))
            <a
                class="absolute top-5 right-5 link"
                href="{{route('training-tickets.edit', ['ticket' => $trainingTicket->id])}}"
            >
                Edit
            </a>
        @endif
        <div class="grid grid-cols-2 w-max gap-x-10">
            <x-label label="Session Date" :value="$trainingTicket->session_start"/>
            <x-label label="Session Duration" :value="$trainingTicket->duration"/>
            <x-label label="Student" :value="$trainingTicket->student->first_name.' '.$trainingTicket->student->last_name"/>
            <x-label label="Instructor" :value="$trainingTicket->instructor->first_name.' '.$trainingTicket->instructor->last_name"/>
            <x-label label="Position" :value="$trainingTicket->position"/>
            <x-label-slot label="Score">
                <div class="rating">
                    <div class="mask mask-star" {{ $trainingTicket->score == 1 ? 'aria-current="true"' : "" }} aria-label="1 star"></div>
                    <div class="mask mask-star" {{ $trainingTicket->score == 2 ? 'aria-current="true"' : "" }} aria-label="2 star"></div>
                    <div class="mask mask-star" {{ $trainingTicket->score == 3 ? 'aria-current="true"' : "" }} aria-label="3 star"></div>
                    <div class="mask mask-star" {{ $trainingTicket->score == 4 ? 'aria-current="true"' : "" }} aria-label="4 star"></div>
                    <div class="mask mask-star" {{ $trainingTicket->score == 5 ? 'aria-current="true"' : ""}} aria-label="5 star"></div>
                </div>
            </x-label-slot>
            <x-label label="Notes" :value="$trainingTicket->notes"/>
        </div>
    </div>
@endsection
