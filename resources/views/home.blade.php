<style>
    video::-webkit-media-controls {
        display: none !important;
    }
</style>

@section('title', 'Home')

@extends('layouts.main')

@section('body-nopad')
        <div class='relative w-full h-full flex flex-col -top-20 justify-center items-center'>
            <video autoplay class='w-full h-full absolute object-cover'
            muted
            loop
            playbackRate='2'
            controls='false'
            disablePictureInPicture
            src={{ asset('images/ZJX_home.mp4') }}>
            </video>

            <div class='font-bold text-accent-content text-center z-10 w-max h-max relative p-10 flex justify-center flex-col items-center'>
                <div class="absolute glass opacity-25 rounded-2xl bg-info h-full w-full">

                </div>
                <h1 class='text-primary text-7xl z-20'>Virtual Jacksonville ARTCC</h1>
                <h2 class='text-primary-content text-5xl z-20'>Pride of VATUSA South</h2>
            </div>
        </div>
@endsection

@section('body')
    <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5">
        <x-card-component title="Online Controllers">

            @unless(sizeof($onlineSessions) == 0)
                @foreach ($onlineSessions as $session)
                    <x-online-controller
                    :callsign='$session->callsign'
                    :user='$session->user'
                    :userId='$session->user_id'
                    :onlineSince='new DateTime($session->start)'/>
                @endforeach
            @else
                <h1 class="text-lg">No controllers online</h1>
            @endunless

        </x-card-component>

        <x-card-component title="Upcoming Events">

        </x-card-component>

        <x-card-component title="News">
            <ul>
                <li class="text-lg">11-25-2025 Jud Lopez promoted to C1</li>
            </ul>
        </x-card-component>

        @if(count($soloCerts) > 0)
            <x-card-component title="Solo Certs">
                <ul>
                    @foreach ($soloCerts as $soloCert)      
                        <x-solo-cert-card :soloCert='$soloCert'/>
                    @endforeach
                </ul>
            </x-card-component>
        @endif
    </div>
@endsection
