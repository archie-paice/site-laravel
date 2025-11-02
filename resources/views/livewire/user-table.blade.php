<div x-data="{ search: @entangle('search').live }">
    <input 
    type='text' 
    x-model.debounce.300ms="search" 
    class='input input-md mb-5' 
    placeholder='Search (Name, CID)'/>

    @unless(sizeof($users) == 0)
        <table class='table table-zebra table-md w-max border-2 border-base-300'>
            <thead>
                <tr class='text-xl font-bold'>
                    <th>ZJX Roster</th>
                </tr>
                <tr>
                    <th class="cursor-pointer px-4 py-2 text-left">CID</th>
                    <th>Name</th>
                    <th>Rating</th>
                    @haspermission('manage roster')
                        <th>Email</th>
                        <th>Joined</th>
                        <th>Last Activity</th>
                    @endhaspermission
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td class='border-r-1 border-base-300'>
                            <a href={{ route('users.show', ['user' => $user->id]) }} class='text-base-content no-underline'>{{ $user->id }}</a>
                        </td>
                        <td class='border-r-1 border-base-300'>
                            <a href={{ route('users.show', ['user' => $user->id]) }} class='text-base-content no-underline'>
                                {{ $user->last_name }}, {{ $user->first_name }}
                            </a>

                            @unless(strcasecmp($user->facility, env('VATUSA_FACILITY')) == 0)
                                <h3 class='badge badge-info badge-sm ml-2'>{{ $user->facility }} Visitor</h3>
                            @endunless
                        </td>
                        <td class='border-r-1 border-base-300'>{{ $user->rating->mapToString() }}</td>
                        @haspermission('manage roster')
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->joined_at }}</td>
                            <td>{{ $user->updated_at }}</td>
                            <td>
                                <ul class='text-accent menu menu-horizontal h-10 items-center gap-x-5 justify-center'>
                                    <li>
                                        <details>
                                            <summary>Actions</summary>
                                            <ul class="bg-base-100 text-base-content rounded-t-none p-2 z-10">
                                                <li><a href={{ route('users.edit', ['user' => $user->id]) }}>Edit</a></li>
                                            </ul>
                                        </details>
                                    </li>
                                </ul>
                            </td>
                        @endhaspermission
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <h1>There are no rostered users.</h1>
    @endunless

    <div class="w-150 mt-5">
        {{ $users->links() }}
    </div>
</div>
