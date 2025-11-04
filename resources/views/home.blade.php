<style>
    video::-webkit-media-controls {
        display: none !important;
    }
</style>

@section('title', 'Home')

@extends('layouts.main')

@section('body-nopad')
        <div class='relative w-lvw h-lvh flex flex-col -top-20 justify-center items-center'>
            <video autoplay class='w-full h-full absolute  object-cover'
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
                <h1 class='text-accent text-7xl z-20'>Virtual Jacksonville ARTCC</h1>
                <h2 class='text-5xl z-20'>Pride of VATUSA South</h2>
            </div>
        </div>
@endsection

@section('body')
    <div class="grid grid-cols-3">
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
    </div>
@endsection
