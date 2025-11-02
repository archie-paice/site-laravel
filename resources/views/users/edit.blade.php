@extends('layouts.main')

@section('body')
    <div class='card w-max bg-base-300 p-5 flex flex-col'>
        <x-user-data :user='$user'/>

        <form action={{ route('users.update', $user) }} method='POST'>
            @csrf
            @method('PUT')
            
            <input hidden name='id' value='{{ $user->id }}'/>

            <div class="grid grid-cols-2 gap-x-20">
                <x-label-slot label='Operating Initials'>
                    <input type="text" 
                    maxlength='2' 
                    class='input input-sm w-20'
                    name='operatingInitials'
                    value='{{ $user->operating_initials }}'
                    />
                </x-label-slot>
            </div>

            <button type="submit" class='btn btn-accent'>Submit Changes</button>
        </form>
    </div>
@endsection