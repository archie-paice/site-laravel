<x-card-component :title="$position">
    <div class="flex flex-col h-full content-stretch">
        <h2 class="text-2xl mb-5">{{ $user->first_name.' '.$user->last_name }}</h2>

        <p class="text-lg">{{$description}}</p>
        <p class="text-lg mt-5"><strong>Reports to:</strong> {{$reportsTo}}</p>
    </div>
</x-card-component>
