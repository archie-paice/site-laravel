@extends('layouts.main')

@section('title', 'ARTCC Staff')

@section('body')
    <div class="w-full grid grid-cols-3 gap-10">
        <x-staff-card
            position="Air Traffic Manager"
            description="The Air Traffic Manager oversees day-to-day operations and collaborates with all staff members of the ARTCC."
            :user="$atm"
            reportsTo="VATUSA"
        />

        <x-staff-card
            position="Deputy Air Traffic Manager"
            description="The Deputy Air Traffic Manager assists the Air Traffic Manager in overseeing the ARTCC. The Deputy Air Traffic Manager also oversees the Web, Events, and Facilities Department and assists as needed."
            :user="$datm"
            reportsTo="Air Traffic Manager"
        />

        <x-staff-card
            position="Training Administrator"
            description="The Training Administrator is responsible for overseeing the training department of the ARTCC."
            :user="$ta"
            reportsTo="Air Traffic Manager"
        />

        <x-staff-card
            position="Events Coordinator"
            description="The Events Coordinator manages the planning and execution of events related to vZJX or our neighbors.."
            :user="$ec"
            reportsTo="Deputy Air Traffic Manager"
        >
            <x-assistant-staff
                positionTitle="Assistant Events Coordinators"
                :staff="$eventsTeam"
            />
        </x-staff-card>

        <x-staff-card
            position="Facility Engineer"
            description="The Facility Engineer is responsible for managing all data related to the VATSIM network, such as standard operating procedures and radar maps."
            :user="$fe"
            reportsTo="Deputy Air Traffic Manager"
        >
            <x-assistant-staff
                positionTitle="Assistant Facility Engineers"
                :staff="$facilitiesTeam"
            />
        </x-staff-card>

        <x-staff-card
            position="Webmaster"
            description="The Webmaster handles all management of data systems in the vZJX network, including the website, relational databases, email systems, and more."
            :user="$wm"
            reportsTo="Deputy Air Traffic Manager"
        >
            <x-assistant-staff
                positionTitle="Assistant Webmasters"
                :staff="$webTeam"
            />
        </x-staff-card>

        <x-card-component title="Training Team">
            <div class="flex flex-col justify-center gap-x-20">
                <div>
                    <h2 class="text-2xl mb-2">Instructors</h2>

                    <div class="flex flex-row flex-wrap gap-x-5">
                        @foreach($instructors as $mentor)
                            <a
                                href="{{route('users.show', ['user' => $mentor->user->id])}}"
                                class="text-lg"
                            >
                                {{$mentor->user->first_name.' '.$mentor->user->last_name}} ({{$mentor->user->rating->mapToString()}})
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="mt-5">
                    <h2 class="text-2xl mb-2">Mentors</h2>

                    <div class="flex flex-row flex-wrap gap-x-5">
                        @foreach($mentors as $mentor)
                            <a
                                href="{{route('users.show', ['user' => $mentor->user->id])}}"
                                class="text-lg"
                            >
                                {{$mentor->user->first_name.' '.$mentor->user->last_name}} ({{$mentor->user->rating->mapToString()}})
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </x-card-component>
    </div>
@endsection
