@extends('layouts.main')

@section('title', 'Profile - '.$user->name)

@section('body')
            <div class='max-h-full w-max'>
                <div role="tablist" class="tabs tabs-lift">
                    <a 
                    role="tab" 
                    href='{{ route("users.show", $user) }}' 
                    @class(['tab', 'tab-active' => request()->routeIs('users.show')])
                    >General Info</a>

                    @role('training')
                    <a 
                    role="tab" 
                    class="tab" 
                    href='{{ route("users.show.training-tickets", $user) }}' 
                    @class(['tab', 'tab-active' => request()->routeIs('users.show.training-tickets')])
                    >Training Tickets</a>
                    <a 
                    role="tab" 
                    class="tab" 
                    href='{{ route("users.show.training-assignments", $user) }}' 
                    @class(['tab', 'tab-active' => request()->routeIs('users.show.training-assignments')])
                    >Training Assignments</a>
                    <a 
                    role="tab" 
                    class="tab" 
                    href='{{ route("users.show.solo-certs", $user) }}' 
                    @class(['tab', 'tab-active' => request()->routeIs('users.show.solo-certs')])
                    >Solo Certs</a>
                    @endrole
                </div>
                
            </div>

            <div class='w-max bg-base-100 border-base-300 border-1 p-2'>
                @yield('profile-content')
            </div>
@endsection