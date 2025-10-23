<style>
    video::-webkit-media-controls {
        display: none !important;
    }
</style>
@extends('layouts.main')

@section('body-nopad')
    <div class='absolute top-0 w-lvw h-lvh flex justify-center items-center'>
        <video autoplay class='w-full h-full absolute object-cover'
        muted
        loop
        playbackRSate='2'
        controls='false'
        disablePictureInPicture
        src={{ asset('images/ZJX_home.mp4') }}>
        </video>

        <div class='font-bold text-accent-content text-center z-10'>
            <h1 class='text-accent text-7xl'>Virtual Jacksonville ARTCC</h1>
            <h2 class='text-5xl'>Pride of VATUSA South</h2>
        </div>
    </div>
@endsection