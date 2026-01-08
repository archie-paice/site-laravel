<x-card-component :title="$position">
    <div class="flex flex-col h-full content-stretch">
        @unless(is_null($staff))
            <a href="{{route('users.show', ['user' => $staff->user->id])}}" class="text-2xl mb-5">{{ $staff->user->first_name.' '.$staff->user->last_name }} ({{$staff->user->rating->mapToString()}})</a>
        @else
            <h2 class="text-2xl mb-5">Vacant</h2>
        @endunless

        <p class="text-lg">{{$description}}</p>
        <p class="text-lg mt-5"><strong>Reports to:</strong> {{$reportsTo}}</p>

        {{$slot}}
    </div>
</x-card-component>
