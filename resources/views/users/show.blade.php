@extends('layouts.main')

@section('title', 'Profile - '.$user->first_name.' '.$user->last_name)

@section('body')
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-2 h-fit">
        <x-card-component title='General Info'>
            <x-user-data :user='$user'/>
        </x-card-component>

        @if(!is_null(auth()->user()) && (auth()->user()->hasRole('training') || auth()->user()->id == $user->id))
        <div class='max-h-full'>
            <div class="tabs tabs-box h-full" x-data="{
                activeTab: localStorage.getItem('activeTab') || 'tickets'
            }">
                {{-- this section uses alpine to save the state of the currently active tab --}}
                <input type="radio" name="my_tabs_6" class="tab" aria-label="Training Tickets"
                @click="activeTab = 'tickets'; localStorage.setItem('activeTab', 'tickets')"
                x-bind:checked='activeTab === "tickets"'
                />
                <div class="tab-content bg-base-100 border-base-300 p-6">
                    <x-training-ticket-table :trainingTickets="$trainingTickets"/>

                    {{ $trainingTickets->links() }}
                </div>

                <input type="radio" name="my_tabs_6" class="tab" aria-label="Training Assignments"
                @click="activeTab = 'assignments'; localStorage.setItem('activeTab', 'assignments')"
                x-bind:checked='activeTab === "assignments"'
                />
                <div class="tab-content bg-base-100 border-base-300 p-2">
                    <x-training-assignments-profile-table :userId="$user->id" :trainingAssignments="$trainingAssignments"/>
                </div>

                <input type="radio" name="my_tabs_6" class="tab" aria-label="Solo Certs"
                @click="activeTab = 'soloCerts'; localStorage.setItem('activeTab', 'soloCerts')"
                x-bind:checked='activeTab === "soloCerts"'
                />

                <div class="tab-content bg-base-100 border-base-300 p-6">
                    <x-solo-certs-table-user-profile :soloCerts="$soloCerts"/>
                </div>

            </div>
        </div>
        @endif

        <x-card-component title='Statistics'>
            <div class='flex flex-row justify-evenly border-b-1'>
                <x-profile-statistics-time-label
                    label='Total Time Online'
                    :time-interval='new DateInterval("PT300H")'/>

                <x-profile-statistics-time-label
                    label='This Month'
                    :time-interval='new DateInterval("PT20H25M")'/>

                <x-profile-statistics-time-label
                    label='This Year'
                    :time-interval='new DateInterval("PT112H35M")'/>
            </div>

            <div class='mt-2'>
                <h1 class='text-xl'>Last 10 Sessions</h1>

                
            </div>
        </x-card-component>
    </div>
@endsection
