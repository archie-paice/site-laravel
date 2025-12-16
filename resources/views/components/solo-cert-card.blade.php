<li class="text-lg mb-2">
    <div class='card border-2 border-base-300'>
        <div class='flex flex-row align-middle justify-left'>
            <img class='w-17 h-17 ml-4 rounded-full place-self-center' src="{{ asset($soloCert->user->profile_image_route) }}" alt="">

            <div class='ml-5 p-4 flex flex-col'>
                <a class='font-bold' href={{ route('users.show', $soloCert->user->id) }} class='text-xl'>{{ $soloCert->user->name }}</a>

                <h2 class='absolute top-4 right-4 text-lg'>{{ $soloCert->position }}</h2>
                <h2 class='text-lg'>Expires on {{ $soloCert->expires->format('Y-m-d') }}</h2>
            </div>
        </div>
    </div>
</li>