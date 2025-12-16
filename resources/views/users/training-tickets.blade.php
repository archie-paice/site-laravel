@extends('layouts.profile')

@section('profile-content')
    <x-training-ticket-table :trainingTickets="$trainingTickets"/>

    {{ $trainingTickets->links() }}
@endsection