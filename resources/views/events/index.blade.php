@extends('layouts.main')

@section('title', 'Upcoming Events and Staffing')

@section('body')
    <div class="flex flex-col justify-center mt-8">
        <livewire:events-calendar/>
{{--        <div class="carousel w-xl mx-auto">--}}
{{--            @foreach ($events as $i => $event)--}}
{{--                <div id="slide{{ $i + 1 }}" class="carousel-item relative w-full">--}}
{{--                    <a href="{{ route('events.show', $event->id) }}">--}}
{{--                    <img src="{{ $event->image_url }}" class="w-full" />--}}
{{--                    </a>--}}
{{--                    <div class="absolute left-5 right-5 top-1/2 flex -translate-y-1/2 transform justify-between">--}}
{{--                        <a href="#slide{{ $i === 0 ? count($events) : $i }}" class="btn btn-circle">❮</a>--}}
{{--                        <a href="#slide{{ $i + 2 > count($events) ? 1 : $i + 2 }}" class="btn btn-circle">❯</a>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            @endforeach--}}
{{--        </div>--}}
        </div>
@endsection
