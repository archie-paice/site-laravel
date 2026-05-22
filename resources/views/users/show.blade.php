@extends('layouts.profile')

@section('profile-content')
<div class='flex flex-col gap-3'>
    <x-card-component title='General Information'>
        <x-user-data :user="$user"/>
    </x-card-component>
    
    <x-card-component title='Statistics'>
        <div class='flex flex-row justify-evenly border-b-1 gap-2 py-2'>
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