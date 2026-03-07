@extends('layouts.admin')

{{-- @section('title', 'Event Manager - ' . $event->name . ' (' . $event->type->value . ')') --}}
@section('title', 'Event Manager')

@section('body')
    <div class="card card-body bg-base-300">
        <form method="POST" action="{{ route('events.store') }}" class="flex flex-col">
            @csrf
            <div class="card bg-base-100 w-full shadow-sm">
                <div class="card-body">
                    <h2 class="card-title">{{ $event->title }} ({{ $event->type->value }})</h2>
                    <h3>Start: {{ $event->start }}</h3>
                    <h3>End: {{ $event->end }}</h3>
                    <p>{{ $event->description }}</p>
                    <div class="card-actions justify-end">
                        <button class="btn btn-primary">Buy Now</button>
                    </div>
                </div>
            </div>
            <div class="card bg-base-100 w-96 shadow-sm">
                <div class="card-body">
                    <h2 class="card-title">Position Presets</h2>
                    <label for="positions" class="label">Positions (type and separate each position with a comma)</label>
                    <input name="positions"
                        value="{{ old('positions', implode(', ', json_decode($positions ?? '[]', true))) }}" type="text"
                        required placeholder="Eg. MCO_GND, MCO_TWR" class="input" />
                    <div class="card-actions justify-end">
                        <button class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
            <div class="card bg-base-100 w-full shadow-sm">
                <div class="card-body">
                    <h2 class="card-title">Controller Positions</h2>
                    <label for="positions" class="label">Positions (type and separate each position with a comma)</label>
                    <input name="positions"
                        value="{{ old('positions', implode(', ', json_decode($positions ?? '[]', true))) }}" type="text"
                        required placeholder="Eg. MCO_GND, MCO_TWR" class="input" />
                    <div class="card-actions justify-end">
                        <button class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
            <div class="collapse bg-base-100 border border-base-300">
                <input type="radio" name="my-accordion-1" checked="checked" />
                <div class="collapse-title font-semibold">Basic Information</div>
                <div class="collapse-content text-sm">
                    <label for="name" class="label">Event Name</label>
                    <input name="name" required type="text" placeholder="Event Name" class="input" />

                    <br />
                    <label for="start" class="label">Event Start</label>
                    <input type="datetime-local" name="start" class="input" required>

                    <label for="end" class="label">Event End</label>
                    <input type="datetime-local" name="end" class="input" required>
                </div>
            </div>
        </form>
    </div>
@endsection
