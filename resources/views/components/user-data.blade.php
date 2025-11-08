<div class="mb-5">
    <h1 class='card-title'>{{ $user->first_name.' '.$user->last_name }}</h1>
    @if ($user->rostered && strcasecmp($user->facility, 'ZJX') == 0)
        <h2 class='text-lg text-accent'>Home Controller</h2>
    @elseif ($user->rostered)
        <h2 class='text-lg text-error'>Visiting Controller</h2>
    @endif
</div>

<div class="grid grid-cols-2 gap-x-20">
    <a href='{{ route('users.edit', $user) }}' class='link absolute top-5 right-5'>Edit User</a>
    <x-label label='CID' :value="$user->id"/>
    <x-label label='Rating' :value="$user->rating->mapToString()"/>

    @unless(is_null($user->operating_initials) || strlen($user->operating_initials) == 0)
        <x-label label='Operating Initials' :value="$user->operating_initials"/>
    @else
        <x-label label='Operating Initials' value="Unassigned"/>
    @endunless

    @if($user->rostered && $user->joined_at != null)
        <x-label label='Member Since' :value='(new DateTime($user->joined_at))->format("M d Y")'/>
    @endif

    @unless(strcasecmp($user->facility, 'ZJX') == 0)
        <x-label label='Home Division' :value='$user->division'/>
        <x-label label='Home Subdivision' :value='$user->facility'/>
    @endunless
</div>
