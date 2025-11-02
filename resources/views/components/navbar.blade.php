<div class="navbar bg-primary text-primary-content z-20">
    <div class="flex-1 ml-5">
        <a href='{{ route('home') }}' class='font-bold text-2xl'>ZJX ARTCC</a>
    </div>

    <ul class='menu menu-horizontal items-center gap-x-5 justify-center'>
        <li>
            <details>
                <summary>Controllers</summary>
                <ul class="bg-base-100 text-base-content rounded-t-none p-2">
                    <li><a href={{ route('roster') }}>Roster</a></li>
                    <li><a>Link 2</a></li>
                </ul>
            </details>
        </li>

        @hasrole('staff')
            <li>
                <details>
                    <summary>Facility Admin</summary>
                    <ul class="bg-base-100 text-base-content rounded-t-none p-2 w-max">
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
                </details>
            </li>
        @endhasrole
        @if(auth()->user())
            <h2></h2>
            <li>
                <details>
                    <summary>{{  auth()->user()->first_name }} {{  auth()->user()->last_name }} - {{  auth()->user()->id }}</summary>
                    <ul class="bg-base-100 text-base-content rounded-t-none p-2">
                        <li><a href={{ route('users.show', ['user' => auth()->user()->id]) }}>Profile</a></li>
                        <li><a href={{ route('auth.logout') }}>Logout</a></li>
                    </ul>
                </details>
            </li>
        @else
            <x-login-button />
        @endif
    </ul>
</div>