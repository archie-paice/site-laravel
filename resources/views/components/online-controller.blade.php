<div class='card bg-sky-100'>
    <div class='p-2 flex flex-col relative'>
        <strong class='text-xl'>{{ $callsign }}</strong>
        <a href={{ route('users.show', $user->id) }} class='text-lg'>{{  $user->first_name.' '.$user->last_name.' - '.$user->id }}</a>
        <h2 class='absolute top-2 right-2 text-lg'>{{ (new DateTime())->diff($onlineSince)->format('%H:%i') }}</h2>
    </div>
</div>