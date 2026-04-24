@extends('layouts.admin')

@section('title', 'Manage Event')

@section('body')
    <div class="flex gap-3">
<div class="flex flex-col gap-10">
    <div class="flex gap-3">
    <div class="card bg-base-100 w-96 shadow-xl">
        <div class="card-body bg-neutral">
            <p class="text-2xl">Registered Controllers</p>
            <h2 class="card-title text-4xl">{{ $registrants->count() }}</h2>
        </div>
    </div>
        <div class="card bg-base-100 w-96 shadow-xl">
            <div class="card-body bg-neutral">
                <p class="text-2xl">Most Requested Position</p>
                <h2 class="card-title text-4xl">{{ $mostRequestedPosition ?? 'N/A'}}</h2>
            </div>
        </div>
    </div>
    <div class="flex flex-col">
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

        <div class="pt-20">
            @livewire('event-registrants', ['event' => $event])
        </div>
    </div>

    <div class="">

    </div>
</div>
        <div class="">
            @livewire('event-positions-management', ['event' => $event])
        </div>
    </div>
@endsection
