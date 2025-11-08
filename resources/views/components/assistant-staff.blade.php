<div class="w-full mt-5 border-t-1 border-base-content pt-2">
    <h2 class="text-2xl mb-2">{{ $positionTitle }}</h2>

    @foreach($staff as $staffMember)
        <a href="{{route('users.show', ['user' => $staffMember->user->id])}}"
           class="text-lg pl-5"
        >
            {{$staffMember->user->first_name.' '.$staffMember->user->last_name}} ({{$staffMember->user->rating->mapToString()}})
        </a>
    @endforeach
</div>
