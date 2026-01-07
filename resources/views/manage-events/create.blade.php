@extends('layouts.admin')

@section('title', 'Create Event')

@section('body')
    <form method="POST" action="{{ route('manage-events.store') }}" class="flex flex-col gap-5">
        @csrf
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

        <div class="collapse bg-base-100 border border-base-300">
            <input type="radio" name="my-accordion-1" checked="checked" />
            <div class="collapse-title font-semibold">Event Type</div>
            <div class="collapse-content text-sm">
                <select name="type" class="select">
                    <option disabled selected>Select type</option>

                    @foreach ($types as $t)
                        <option value="{{ $t->name }}">
                            {{ str_replace('_', ' ', $t->name) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="collapse bg-base-100 border border-base-300">
            <input type="radio" name="my-accordion-1" checked="checked" />
            <div class="collapse-title font-semibold">Description</div>
            <div class="collapse-content text-sm">
                <textarea name="description" required class="textarea" placeholder="Event description..."></textarea>
            </div>
        </div>

        <div class="collapse bg-base-100 border border-base-300">
            <input type="radio" name="my-accordion-1" checked="checked" />
            <div class="collapse-title font-semibold">Banner URL</div>
            <div class="collapse-content text-sm">
                <label for="image_url" class="label">Banner URL</label>
                <input name="image_url" type="url" placeholder="image.jpg" class="input" />
            </div>
        </div>

        <div class="collapse bg-base-100 border border-base-300">
            <input type="radio" name="my-accordion-1" checked="checked" />
            <div class="collapse-title font-semibold">Position Preset</div>
            <div class="collapse-content text-sm">
                <select name="presetPositions" class="select">
                    <option disabled selected>Select preset</option>

                    @foreach ($presetPositions as $p)
                        <option value="{{ $p }}">
                            {{ str_replace('_', ' ', $p) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>


        <div class="collapse bg-base-100 border border-base-300">
            <input type="radio" name="my-accordion-1" checked="checked" />
            <div class="collapse-title font-semibold">Featured Fields</div>
            <div class="collapse-content text-sm">
                <label for="featured_fields" class="label">Featured Fields (ICAO or IATA fields only, separated by commas)</label>
                <input name="featured_fields" type="text" required placeholder="Eg. KMCO, KJAX, KDAB, KORL" class="input" />
            </div>
        </div>

        <div class="collapse bg-base-100 border border-base-300">
            <input type="radio" name="my-accordion-1" checked="checked" />
            <div class="collapse-title font-semibold">Important Event Information</div>
            <div class="collapse-content text-sm">
                By default, when an event is created, it is hidden from the calendar or list view.
                This event will be archived, not deleted, 24 hours after the published end date. This can be reverted
                through the event manager.
                Create, publish, modify, and delete positions through the Event Manager, which you will be redirected to
                after submission.
                Editing this event later on will have no impact on the Event Manager, since it only deals with
                positions.
                You can un-hide this event from the events manager among other common tasks.
                You will be notified of any errors in your submission after you submit the form. All changes will be
                saved as long as you dont leave this page or refresh.
            </div>
        </div>
        
        <button class="btn btn-primary" type="submit">Create Event</button>
    </form>

@endsection
