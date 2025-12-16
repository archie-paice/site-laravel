@extends('layouts.admin')

@section('body')
    <div class='flex flex-wrap gap-10'>
        <x-card-component title="ARTCC Membership Overview">
            <div class="mt-5">
                <h2 class='text-2xl'><strong>Home:</strong> {{ $homeControllers }}</h2>
                <h2 class='text-2xl'><strong>Visiting:</strong> {{ $visitingControllers }}</h2>
                <h2 class='text-2xl'><strong>Total:</strong> {{ $homeControllers + $visitingControllers }}</h2>
            </div>
        </x-card-component>

        @role('training')
            <x-card-component title="Training Quick Links">
                <a class='btn btn-primary mt-5' href="{{ route('training-assignments.index') }}">My Students (TODO)</a>
                <a class='btn btn-primary' href="{{ route('training-assignments.index') }}">Training Assignments</a>
                <a class='btn btn-primary' href="{{ route('training-tickets.create') }}">Create Training Ticket</a>
                <a class='btn btn-primary' href="{{ route('training-tickets.create') }}">Issue Solo Cert</a>
            </x-card-component>
        @endrole

        @role('facilities')
            <x-card-component title="Facilities Quick Links">
                <a class='btn btn-primary mt-5' href="{{ route('statistics-prefixes.index') }}">Statistics Prefixes</a>
                <a class='btn btn-primary' href="{{ route('statistics-prefixes.index') }}">Document Management</a>
            </x-card-component>
        @endrole

        @role('events')
            <x-card-component title="Events Quick Links">
                <a class='btn btn-primary mt-5' href="{{ route('training-assignments.index') }}">horse tinder idk</a>
            </x-card-component>
        @endrole
    </div>
@endsection