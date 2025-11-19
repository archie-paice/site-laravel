@extends('layouts.main')

@section('body')
    <div class='card w-max bg-base-300 p-5'>
        <div class="mb-5">
            <h1 class='card-title'>{{ $event->name }}</h1>
            {{-- @if ($event->rostered && strcasecmp($event->facility, 'ZJX') == 0)
                <h2 class='text-lg text-accent'>Home Controller</h2>
            @elseif ($event->rostered)
                <h2 class='text-lg text-error'>Visitng Controller</h2>
            @endif --}}
        </div>

        <div class="grid grid-cols-2 gap-x-20">
            {{-- <x-label label='CID' :value="$event->id"/>
            <x-label label='Rating' :value="$event->rating->mapToString()"/>
            
            @if($event->rostered && $event->joined_at != null)
                <x-label label='Member Since' :value='new DateTime($event->joined_at)->format("M d Y")'/>
            @endif

            @unless(strcasecmp($event->facility, 'ZJX') == 0)
                <x-label label='Home Division' :value='$event->division'/>
                <x-label label='Home Subdivision' :value='$event->facility'/>
            @endunless --}}
        </div>
    </div>
@endsection