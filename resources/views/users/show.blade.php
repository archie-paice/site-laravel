@extends('layouts.main')

@section('body')
    <div class='card w-max bg-base-300 p-5'>
        <h1 class='text-xl font-bold'>{{ $user->first_name.' '.$user->last_name }}</h1>
        @if ($user->rostered && strcasecmp($user->facility, 'ZJX') == 0)
            <h2 class='text-lg text-accent'>Home Controller</h2>
        @elseif ($user->rostered)
            <h2 class='text-lg text-error'>Visitng Controller</h2>
        @endif
        <x-label-component label='CID' :value="$user->id"/>
        <x-label-component label='Rating' :value="$user->rating->mapToString()"/>
        </div>
@endsection