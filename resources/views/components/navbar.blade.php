<div class="navbar sticky top-0 bg-primary text-primary-content z-20">
    <div class="flex-1 ml-5">
        <a href='{{ route('home') }}' class='font-bold text-2xl'>
            <img src="{{ asset('images/zjx_wide.png') }}" alt="">
        </a>
    </div>

    <ul class='menu menu-horizontal items-center gap-x-5 justify-center'>
        <div class="dropdown dropdown-end">
            <div tabindex="0" role="button" class="m-1 flex items-center gap-2">
                <span>Controllers</span>
                <x-dropdown-icon/>
            </div>
            <ul tabindex="-1" class="dropdown-content text-base-content menu bg-base-100 rounded-box z-1 w-52 p-2 shadow-sm">
                <li><a href="{{ route('roster.index') }}">Roster</a></li>
                <li><a href="{{ route('staff.index') }}">Facility Staff</a></li>
            </ul>
        </div>

        @hasrole('staff')
            <div class="dropdown dropdown-end">
                <div tabindex="0" role="button" class="m-1 flex items-center gap-2">
                    <span>Facility Admin</span>
                    <x-dropdown-icon/>
                </div>
                <ul tabindex="-1" class="dropdown-content text-base-content menu bg-base-100 rounded-box z-1 w-52 p-2 shadow-sm">
                    <li><a href={{ route('admin.index') }}>Dashboard</a></li>

                    @hasrole('training')
                        <li><a href={{ route('admin.index') }}>Training Management</a></li>
                    @endhasrole

                    @hasrole('facilities')
                        <li><a href={{ route('admin.index') }}>Data Management</a></li>
                    @endhasrole

                    @hasrole('events')
                        <li><a href={{ route('admin.index') }}>Events Management</a></li>
                    @endhasrole

                    @hasrole('admin')
                        <li><a href={{ route('admin.index') }}>Admin</a></li>
                    @endhasrole
                </ul>
            </div>
        @endhasrole
        @if(auth()->user())
            <div class="dropdown dropdown-end">
                <div tabindex="0" role="button" class="m-1 flex items-center gap-2">
                    <span>{{ auth()->user()->first_name }} {{ auth()->user()->last_name }} - {{ auth()->user()->id }}</span>
                    <x-dropdown-icon/>
                </div>
                <ul tabindex="-1" class="dropdown-content text-base-content menu bg-base-100 rounded-box z-1 w-52 p-2 shadow-sm">
                    <li><a href="{{ route('users.show', [auth()->user()->id]) }}">Profile</a></li>
                    <li><a href="{{ route('auth.logout') }}">Logout</a></li>
                </ul>
            </div>
        @else
            <x-login-button />
        @endif
    </ul>
</div>
