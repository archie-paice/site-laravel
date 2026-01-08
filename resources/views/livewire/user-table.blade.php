<div x-data="{ search: @entangle('search').live }">
    <x-search/>

    @unless(sizeof($users) == 0)
        <table class='table table-zebra table-md w-max border-2 rounded-md border-base-300 mt-5'>
            <thead>
                <tr class='text-xl font-bold'>
                    <th>ZJX Roster</th>
                </tr>
                <tr>
                    <th>Name (CIDs)</th>
                    <th>OIs</th>
                    <th>Rating</th>
                    @haspermission('manage users')
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
                            <a href={{ route('users.show', ['user' => $user->id]) }} class='text-base-content no-underline'>
                                {{ $user->nameReversed }}  ({{ $user->id }})
                                
                                @unless(is_null($user->operating_initials) || strlen($user->operating_initials) == 0)
                                    ({{ $user->operating_initials }})
                                @endunless
                            </a>

                            @unless(strcasecmp($user->facility, env('VATUSA_FACILITY')) == 0)
                                <h3 class='badge badge-info badge-sm ml-2'>{{ $user->facility }} Visitor</h3>
                            @endunless
                        </td>
                        <td class='border-r-1 border-base-300' @class(['bg-red-200' => is_null($user->operating_initials) || strlen($user->operating_initials) == 0])>
                            @if(!is_null($user->operating_initials) && strlen($user->operating_initials) > 0)
                                {{ $user->operating_initials }}
                            @else
                                Unassigned
                            @endif
                        </td>
                        <td class='border-r-1 border-base-300'>{{ $user->rating->mapToString() }}</td>
                        
                        @haspermission('manage users')
                            <td class='border-r-1 border-base-300'>{{ $user->email }}</td>
                            <td class='border-r-1 border-base-300'>{{ $user->joined_at->format('Y-m-d') }}</td>
                            <td class='border-r-1 border-base-300'>{{ $user->updated_at->format('Y-m-d') }}</td>
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
