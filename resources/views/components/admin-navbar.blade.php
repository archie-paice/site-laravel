@php
    use \App\Models\VisitorRequest;
    use \App\Enums\VisitRequestStatus;
    $pendingVisitorRequests = VisitorRequest::where('status', VisitRequestStatus::PENDING)->count();
@endphp

<div class="navbar bg-info text-white z-10">
    <div class="flex-1 ml-5">
        <a href='{{ route('admin.index') }}' class='text-xl'>Admin Actions</a>
    </div>

    {{-- Desktop nav (hidden on mobile) --}}
    <ul class='hidden md:flex menu menu-horizontal items-center gap-x-5 justify-center'>
        @hasrole('training')
            <div class="dropdown dropdown-end">
                <div tabindex="0" role="button" class="m-1 flex items-center gap-2">
                    <span>Training Admin</span>
                    <x-dropdown-icon/>
                </div>
                <ul tabindex="-1" class="dropdown-content text-base-content menu bg-base-100 rounded-box z-1 w-52 p-2 shadow-sm">
                    <li><a href={{ route('training-tickets.index') }}>Training Tickets</a></li>
                    <li><a href={{ route('training-assignments.index') }}>Training Assignments</a></li>
                    <li><a href={{ route('solo-certs.index') }}>Solo Certs</a></li>
                </ul>
            </div>
        @endhasrole

        @hasrole('facilities')
            <div class="dropdown dropdown-end">
                <div tabindex="0" role="button" class="m-1 flex items-center gap-2">
                    <span>Data Admin</span>
                    <x-dropdown-icon/>
                </div>
                <ul tabindex="-1" class="dropdown-content text-base-content menu bg-base-100 rounded-box z-1 w-52 p-2 shadow-sm">
                    @haspermission('manage statistics prefixes')
                    <li><a href="{{ route('statistics-prefixes.index') }}">Statistics Prefixes</a></li>
                    @endhaspermission

                    @haspermission('manage certification facilities')
                        <li><a href={{ route('certification-facilities.index') }}>Certification Facilities</a></li>
                    @endhaspermission
                    <li><a href="{{ route('admin.publications.index') }}">Document Management</a></li>
                </ul>
            </div>
        @endhasrole

        @hasrole('events')
            <div class="dropdown dropdown-end">
                <div tabindex="0" role="button" class="m-1 flex items-center gap-2">
                    <span>Events Admin</span>
                    <x-dropdown-icon/>
                </div>
                <ul tabindex="-1" class="dropdown-content text-base-content menu bg-base-100 rounded-box z-1 w-52 p-2 shadow-sm">
                    <li><a href={{ route('admin.events.index') }}>Events</a></li>
                    <li><a href="{{ route('admin.events.position-presets.index') }}">Position Presets</a></li>
                    <li><a href="{{ route('admin.events.event-fields.index') }}">Event Field Presets</a></li>
                    <li><a href={{ route('admin.index') }}>Staffing Requests</a></li>
                </ul>
            </div>
        @endhasrole

        @hasrole('admin')
            <div class="dropdown dropdown-end">
                <div tabindex="0" role="button" class="m-1 flex items-center gap-2">
                    <span>Facility Admin</span>
                    <x-dropdown-icon/>
                </div>
                <ul tabindex="-1" class="dropdown-content text-base-content menu bg-base-100 rounded-box z-1 w-52 p-2 shadow-sm">
                    <li><a href={{ route('admin.index') }}>Dashboard</a></li>
                    <li><a href={{ route('manage-users.index') }}>User Management</a></li>
                    <li>
                        <a href={{ route('visit.manage') }}>Visitor Requests
                            @if($pendingVisitorRequests > 0)
                                <span class='badge badge-primary'>{{ $pendingVisitorRequests }}</span>
                            @endif
                        </a>
                    </li>
                    <li><a href={{ route('logs.index') }}>Audit Log</a></li>
                </ul>
            </div>
        @endhasrole
    </ul>

    {{-- Mobile hamburger (visible on mobile only) --}}
    <div class="md:hidden dropdown dropdown-end mr-3">
        <button tabindex="0" class="btn btn-ghost btn-sm px-2" aria-label="Admin Menu">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
        </button>
        <ul tabindex="-1" class="dropdown-content text-base-content menu bg-base-100 rounded-box z-50 w-56 p-3 shadow-lg mt-2 space-y-0.5">
            @hasrole('training')
                <li class="menu-title text-xs uppercase tracking-wide pt-2">Training Admin</li>
                <li><a href={{ route('training-tickets.index') }}>Training Tickets</a></li>
                <li><a href={{ route('training-assignments.index') }}>Training Assignments</a></li>
                <li><a href={{ route('solo-certs.index') }}>Solo Certs</a></li>
            @endhasrole

            @hasrole('facilities')
                <li class="menu-title text-xs uppercase tracking-wide pt-2">Data Admin</li>
                @haspermission('manage statistics prefixes')
                    <li><a href="{{ route('statistics-prefixes.index') }}">Statistics Prefixes</a></li>
                @endhaspermission
                @haspermission('manage certification facilities')
                    <li><a href={{ route('certification-facilities.index') }}>Certification Facilities</a></li>
                @endhaspermission
                <li><a href="{{ route('admin.publications.index') }}">Document Management</a></li>
            @endhasrole

            @hasrole('events')
                <li class="menu-title text-xs uppercase tracking-wide pt-2">Events Admin</li>
                <li><a href={{ route('admin.events.index') }}>Events</a></li>
                <li><a href="{{ route('admin.events.position-presets.index') }}">Position Presets</a></li>
                <li><a href="{{ route('admin.events.event-fields.index') }}">Event Field Presets</a></li>
                <li><a href={{ route('admin.index') }}>Staffing Requests</a></li>
            @endhasrole

            @hasrole('admin')
                <li class="menu-title text-xs uppercase tracking-wide pt-2">Facility Admin</li>
                <li><a href={{ route('admin.index') }}>Dashboard</a></li>
                <li><a href={{ route('manage-users.index') }}>User Management</a></li>
                <li>
                    <a href={{ route('visit.manage') }}>Visitor Requests
                        @if($pendingVisitorRequests > 0)
                            <span class='badge badge-primary'>{{ $pendingVisitorRequests }}</span>
                        @endif
                    </a>
                </li>
                <li><a href={{ route('logs.index') }}>Audit Log</a></li>
            @endhasrole
        </ul>
    </div>
</div>
