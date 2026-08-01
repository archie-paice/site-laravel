@extends('layouts.main')

@section('title', 'Upcoming Events and Staffing')

@section('body')
    <div class="flex justify-center mt-8">
        <div class="carousel w-xl mx-auto">
            @foreach ($events as $i => $event)
                <div id="slide{{ $i + 1 }}" class="carousel-item relative w-full">
                    <a href="{{ route('events.show', $event->id) }}">
                    <img src="{{ $event->image_url }}" class="w-full" />
                    </a>
                    <div class="absolute left-5 right-5 top-1/2 flex -translate-y-1/2 transform justify-between">
                        <a href="#slide{{ $i === 0 ? count($events) : $i }}" data-carousel-nav class="btn btn-circle">❮</a>
                        <a href="#slide{{ $i + 2 > count($events) ? 1 : $i + 2 }}" data-carousel-nav class="btn btn-circle">❯</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        document.querySelectorAll('[data-carousel-nav]').forEach((link) => {
            link.addEventListener('click', (e) => {
                const target = document.querySelector(link.getAttribute('href'));
                if (!target) return;
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'start' });
            });
        });
    </script>
@endsection
