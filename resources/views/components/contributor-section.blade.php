@props(['title', 'contributors', 'emptyMessage' => null])

@if($contributors->isNotEmpty() || $emptyMessage)
    <x-card-component :title="$title">
        @if($contributors->isEmpty())
            <p class="mt-4 text-base-content/60">{{ $emptyMessage }}</p>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4 mt-4">
                @foreach($contributors as $c)
                    <x-contributor-card :contributor="$c" />
                @endforeach
            </div>
        @endif
    </x-card-component>
@endif
