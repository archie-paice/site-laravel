@extends('layouts.admin')

@section('title', 'Training Ticket - #'.$trainingTicket->id)

@section('body')
    <div class="card card-body bg-base-300 w-max">
        <div class="grid grid-cols-2 w-max gap-x-10">
            <x-label label="Session Date" :value="$trainingTicket->session_date"/>
            <x-label label="Session Duration" :value="$trainingTicket->duration"/>
            <x-label label="Student" :value="$trainingTicket->student->first_name.' '.$trainingTicket->student->last_name"/>
            <x-label label="Instructor" :value="$trainingTicket->instructor->first_name.' '.$trainingTicket->instructor->last_name"/>
            <x-label label="Position" :value="$trainingTicket->position"/>
            <x-label label="Score" :value="$trainingTicket->score"/>
            <x-label label="Notes" :value="$trainingTicket->notes"/>
        </div>
    </div>
@endsection
