<x-card-component :title="$position">
    <div class="flex flex-col h-full content-stretch">
        @unless(is_null($user))
            <a href="{{route('users.show', ['user' => $user->id])}}" class="text-2xl mb-5">{{ $user->first_name.' '.$user->last_name }} ({{$user->rating->mapToString()}})</a>
        @else
            <h2 class="text-2xl mb-5">Vacant</h2>
        @endunless

        <p class="text-lg">{{$description}}</p>
        <p class="text-lg mt-5"><strong>Reports to:</strong> {{$reportsTo}}</p>

        {{$slot}}
    </div>
</x-card-component>
