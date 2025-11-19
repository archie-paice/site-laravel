@extends('layouts.admin')

@section('title', 'Event Management')

@section('body')
    <button class="btn btn-primary">
        <a href={{ route('events.create') }} class='text-base-content no-underline'>+ New Event</a>
    </button>
    @livewire('event-table', ['events' => $events])
@endsection
