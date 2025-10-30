<style>
    video::-webkit-media-controls {
        display: none !important;
    }
</style>
@extends('layouts.main')

@section('body-nopad')
<div class="h-full overflow-hidden">
    <div class='top-0 w-svw h-max flex justify-center items-center p-0'>
        <video autoplay class='left-0 top-0 w-full h-full absolute object-cover'
        muted
        loop
        playbackRSate='2'
        controls='false'
        disablePictureInPicture
        src={{ asset('images/ZJX_home.mp4') }}>
        </video>

        <div class='mt-10 font-bold text-accent-content text-center z-10'>
            <h1 class='text-accent text-7xl'>Virtual Jacksonville ARTCC</h1>
            <h2 class='text-5xl'>Pride of VATUSA South</h2>
        </div>
    </div>
</div>

    <div class='p-5'>
        <h1>THIS IS A WEBSITE</h1>
        <h1>IT RENDERS HTML AND CSS AND RUNS JAVASCRIPT. NO OTHER BS!</h1>
    </div>
@endsection