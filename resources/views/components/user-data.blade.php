<div class="w-max mb-5">
    @if ($user->rostered && strcasecmp($user->facility, 'ZJX') == 0)
        <h2 class='badge badge-lg badge-accent'>Home Controller</h2>
    @elseif ($user->rostered)
        <h2 class='badge badge-lg badge-error'>Visiting Controller</h2>
    @else
        <h2 class='text-lg'>Not Rostered</h2>
    @endif
</div>

<div class="grid grid-cols-2 gap-x-20 w-max">
    @if (auth()->user() && (auth()->user()->id == $user->id || auth()->user()->hasRole('admin')))
        <a href='{{ route('users.edit', $user) }}' class='link absolute top-5 right-5'>Edit User</a>
    @endif

    <img class='col-span-2 border-2 rounded-full w-50 h-50 mb-5' src="{{ asset($user->profile_image_route) }}" alt=""/>

    <x-label label='CID' :value="$user->id"/>
    <x-label label='Rating' :value="$user->rating->mapToString()"/>

    @if($user->rostered)
        <x-label label='Operating Initials' :value="$user->operating_initials"/>
    @endif

    @if($user->rostered && $user->joined_at != null)
        <x-label label='Member Since' :value='(new DateTime($user->joined_at))->format("M d Y")'/>
    @endif

    @unless(strcasecmp($user->facility, 'ZJX') == 0)
        <x-label label='Home Division' :value='$user->division'/>
        <x-label label='Home Subdivision' :value='$user->facility'/>
    @endunless

    @hasrole('staff')
        <x-label label="Email" :value="$user->email"/>
    @endhasrole

    <div class='col-span-2'>
        <x-label-slot label='Biography'>
            <textarea class='textarea w-full resize-none h-30' disabled>{{ $user->biography }}</textarea>
        </x-label-slot>
    </div>
</div>
