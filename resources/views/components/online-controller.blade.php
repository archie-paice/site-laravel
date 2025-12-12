<div class='card border-2 border-base-300 mt-2'>
    <div class='flex flex-row align-middle justify-left'>
            <img class='w-17 h-17 ml-4 rounded-full place-self-center' src="{{ asset($user->profile_image_route) }}" alt="">
            
            <div class='ml-5 p-4 flex flex-col relative w-full'>
                <strong class='text-xl'>{{ $callsign }}</strong>

                @unless(is_null($user))
                    <a href={{ route('users.show', $user->id) }} class='text-lg'>{{  $user->name.' - '.$user->id }}</a>
                @else
                    <a class='text-lg'>Unknown User - {{ $userId }}</a>
                @endUnless
                <h2 class='absolute top-4 right-4 text-lg'>{{ (new DateTime())->diff($onlineSince)->format('%H:%I') }}</h2>
            </div>
        </div>
</div>