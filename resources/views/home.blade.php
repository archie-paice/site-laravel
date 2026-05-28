@extends('layouts.main')


@section('body-nopad')
    <div
        class="hero h-[50vh] w-full bg-cover bg-center"
        style="background-image: url('{{ asset('images/fake_background.png') }}')"
    >
        <div class="hero-overlay bg-black/50"></div>

        <div class="hero-content w-full max-w-none justify-start px-10">
            <div class="max-w-3xl text-left text-white">
                <h1 class="mb-5 text-xl font-bold text-warning">Virtual Jacksonville ARTCC </h1>
                <h1 class="mb-5 text-5xl font-bold">Connecting Controllers.</h1>
                <h1 class="mb-5 text-5xl font-bold">Elevating Excellence.</h1>

                <p class="mb-5">
                    The vZJX VATSIM Division is dedicated to providing exceptional air traffic control services in a realistic, professional,
                    and welcoming virtual environment.
                </p>

                <div class="">
                    <a href="{{ route('auth.redirect') }}"><button class="btn btn-warning shadow-sm">Get Started</button></a>
                    <button class="btn btn-outline btn-default mx-5">Learn more</button>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('body')
    <div class="grid lg:grid-cols-4 md:grid-cols-2 grid-cols-1 mx-auto">

        <div class="bg-base-100 rounded-xl shadow-md p-6 w-full max-w-md border border-base-300 hover:shadow-2xl transition-all duration-300">

            <!-- Icon -->
            <div class="bg-primary text-white rounded-2xl w-14 h-14 flex items-center justify-center text-2xl mb-5">
                <i class="fa-solid fa-users"></i>
            </div>

            <!-- Title -->
            <h2 class="text-2xl font-bold mb-3">
                Community Driven
            </h2>

            <!-- Description -->
            <p class="text-base-content/70 leading-relaxed mb-6">
                Join a thriving network of controllers and pilots focused on realism,
                professionalism, and training excellence.
            </p>

            <!-- Button -->
            <h1 class="font-bold text-primary"><a href="{{ route('events.index') }}">Join Us</a></h1>

        </div>

        <div class="bg-base-100 rounded-xl shadow-md p-6 w-full max-w-md border border-base-300 hover:shadow-2xl transition-all duration-300">

            <!-- Icon -->
            <div class="bg-primary text-white rounded-2xl w-14 h-14 flex items-center justify-center text-2xl mb-5">
                <i class="fa-solid fa-calendar"></i>
            </div>

            <!-- Title -->
            <h2 class="text-2xl font-bold mb-3">
                Events
            </h2>

            <!-- Description -->
            <p class="text-base-content/70 leading-relaxed mb-6">
                Participate in exciting events and comprehensive coverage operations
            </p>

            <!-- Button -->
            <h1 class="font-bold text-primary"><a href="{{ route('events.index') }}">View Events</a></h1>

        </div>

        <div class="bg-base-100 rounded-xl shadow-md p-6 w-full max-w-md border border-base-300 hover:shadow-2xl transition-all duration-300">

            <!-- Icon -->
            <div class="bg-primary text-white rounded-2xl w-14 h-14 flex items-center justify-center text-2xl mb-5">
                <i class="fa-solid fa-folder-open"></i>
            </div>

            <!-- Title -->
            <h2 class="text-2xl font-bold mb-3">
                Resources
            </h2>

            <!-- Description -->
            <p class="text-base-content/70 leading-relaxed mb-6">
                Access charts, procedures, guides, and other essential resources.
            </p>

            <!-- Button -->
            <h1 class="font-bold text-primary"><a href="{{ route('events.index') }}">Browse Resources</a></h1>

        </div>

        <div class="bg-base-100 rounded-xl shadow-md p-6 w-full max-w-md border border-base-300 hover:shadow-2xl transition-all duration-300">

            <!-- Icon -->
            <div class="bg-primary text-white rounded-2xl w-14 h-14 flex items-center justify-center text-2xl mb-5">
                <i class="fa-solid fa-headset"></i>
            </div>

            <!-- Title -->
            <h2 class="text-2xl font-bold mb-3">
                Controllers
            </h2>

            <!-- Description -->
            <p class="text-base-content/70 leading-relaxed mb-6">
                Information and tools for active controllers in the vZJX division.
            </p>

            <!-- Button -->
            <h1 class="font-bold text-primary"><a href="{{ route('events.index') }}">Controller Resources</a></h1>

        </div>
    </div>


    <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5 pt-5">
        <div class="bg-base-100 rounded-xl shadow-xl p-6 w-full max-w-2xl border border-base-300">
            <div class="flex justify-between">
                <h1 class="font-bold">Upcoming Events</h1>
                <h1 class="font-bold text-primary"><a href="{{ route('events.index') }}">View All Events</a></h1>
            </div>

            @foreach($events as $event)

            <div class="flex flex-col my-5">
                <div class="bg-base-100 rounded-3xl shadow-xl border border-base-300/50 overflow-hidden">
                    <div class="flex">

                        <!-- Date side -->
                        <div class="bg-primary text-primary-content w-24 flex flex-col items-center justify-center p-4">
                            <span class="text-sm font-semibold uppercase">{{ $event->start->format('M') }}</span>
                            <span class="text-4xl font-bold leading-none">{{ $event->start->format('d') }}</span>
                        </div>

                        <!-- Event info -->
                        <div class="p-5 flex-1">
                            <h3 class="font-bold text-lg">
                                {{ $event->title }}
                            </h3>

                            <p class="text-sm text-base-content/70 mt-1">
                                {{$event->getFormattedTimeAttribute()}}
                            </p>

                            <p class="text-sm text-base-content/60 mt-2">
                                {{ $event->description }}
                            </p>
                        </div>

                    </div>
                </div>
            </div>
            @endforeach

        </div>

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



        <x-card-component title="News">
            <ul>
                <li class="text-lg">11-25-2025 Jud Lopez promoted to C1</li>
            </ul>
        </x-card-component>

        @if(count($soloCerts) > 0)
            <x-card-component title="Solo Certs">
                <ul>
                    @foreach ($soloCerts as $soloCert)
                        <x-solo-cert-card :soloCert='$soloCert'/>
                    @endforeach
                </ul>
            </x-card-component>
        @endif
    </div>
@endsection
