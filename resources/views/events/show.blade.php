@extends('layouts.main')

@section('body')
    <div class="flex flex-col justify-center items-center gap-6">
        <div class="card card-dash bg-base-100 w-xl shadow-sm">
            @if ($event->event_image_route)
            <figure>
                <img class='' src="{{ asset($event->event_image_route) }}" alt=""/>
            </figure>
            @endif
            <div class="card-body bg-neutral">
                <h1 class="card-title">
                    {{ $event->title }}
                    <div class="badge badge-secondary">{{ $event->type }}</div>
                </h1>
                <h2>
                    {{ $event->start }} - {{ $event->end }}
                </h2>
                @if ($event->featured_fields)
                    <p>{{ implode(', ', $event->featured_fields) }}</p>
                @else
                    <p>No fields</p>
                @endif
                <br />
                <p>{{ $event->description }}</p>
            </div>
        </div>

        @auth
        <div class="card bg-base-100 w-xl shadow-sm">
            @livewire('event-registration', ['event' => $event])
        </div>
        @endauth
    </div>
@endsection
