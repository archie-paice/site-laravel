<div class="navbar bg-info text-primary-content z-10">
    <div class="flex-1 ml-5">
        <a href='{{ route('admin.index') }}' class='text-xl'>Admin Actions</a>
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

        @hasrole('training')
            <li>
                <details>
                    <summary>Training Management</summary>
                    <ul class="bg-base-100 text-base-content rounded-t-none p-2">
                        <li><a href={{ route('admin.index') }}>Admin Dashboard</a></li>
                    </ul>
                </details>
            </li>
        @endhasrole

        @hasrole('facilities')
            <li>
                <details>
                    <summary>Data Management</summary>
                    <ul class="bg-base-100 text-base-content rounded-t-none p-2">
                        <li><a href={{ route('statistics-prefixes.index') }}>Statistics Prefixes</a></li>
                    </ul>
                </details>
            </li>
        @endhasrole


        @hasrole('events')
            <li>
                <details>
                    <summary>Event Management</summary>
                    <ul class="bg-base-100 text-base-content rounded-t-none p-2">
                        <li><a href={{ route('admin.index') }}>Admin Dashboard</a></li>
                    </ul>
                </details>
            </li>
        @endhasrole

        @hasrole('admin')
            <li>
                <details>
                    <summary>Facility Admin</summary>
                    <ul class="bg-base-100 text-base-content rounded-t-none p-2">
                        <li><a href={{ route('admin.index') }}>Dashboard</a></li>
                        <li><a href={{ route('users.index') }}>User Management</a></li>
                    </ul>
                </details>
            </li>
        @endhasrole
    </ul>
</div>