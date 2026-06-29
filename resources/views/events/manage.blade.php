@extends('layouts.admin')

@section('title', 'Manage Event')

@section('body')
    <div class="flex gap-6 w-full">
        <div class="flex flex-col gap-10 flex-1 min-w-0">
            <div class="bg-base-100 rounded-md p-6 w-full border-2 border-base-300 hover:shadow-2xl transition-all duration-300">

                <h2 class="text-2xl font-light mb-3">
                    Event Statistics
                </h2>

                <div class="divide-y divide-base-300">

                    <div class="py-3">
                        <h3 class="font-bold text-primary">
                            Registered Controllers
                        </h3>
                        <p class="text-4xl">
                            {{ $registrants->count() }}
                        </p>
                    </div>


                    <div class="py-3">
                        <h3 class="font-bold text-primary">
                            Most Requested Position
                        </h3>
                        <p class="text-4xl">
                            {{ $mostRequestedPosition ?? 'N/A' }}
                        </p>
                    </div>

                    <div class="py-3">
                        <h3 class="font-bold text-primary">Visible</h3>
                        <form method="POST" action="{{ route('admin.event.visibility', $event) }}">
                            @csrf
                            @method('PATCH')

                            <label class="flex items-center gap-3 cursor-pointer">
                                <input
                                    type="checkbox"
                                    name="visible"
                                    class="toggle toggle-success toggle-xl"
                                    onchange="this.form.submit()"
                                    {{ !$event->hidden ? 'checked' : '' }}
                                />
                            </label>
                        </form>
                    </div>

                    <div class="py-3">
                        <h3 class="font-bold text-primary">
                            Positions Locked
                        </h3>
                        <form method="POST" action="{{ route('admin.event.positions-locked', $event) }}">
                            @csrf
                            @method('PATCH')

                            <label class="flex items-center gap-3 cursor-pointer">
                                <input
                                    type="checkbox"
                                    name="positions_locked"
                                    class="toggle toggle-success toggle-xl"
                                    onchange="this.form.submit()"
                                    {{ $event->positions_locked ? 'checked' : '' }}
                                />
                            </label>
                        </form>
                    </div>

                </div>

            </div>
            <div class="flex flex-col">

                <div class="w-full pt-5">
                    @livewire('event-positions-management', ['event' => $event])
                </div>
                <div class="pt-20">
                    @livewire('event-registrants', ['event' => $event])
                </div>
            </div>
        </div>

        <div class="w-1/3 flex flex-col justify-start items-center gap-6">
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
                        {{ $event->getFormattedRangeAttribute() }}
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
        </div>

    </div>
@endsection
