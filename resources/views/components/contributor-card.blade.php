@props(['contributor'])

@php
    $url = $contributor['html_url'] ?? null;
    $login = $contributor['login'] ?? null;
    $name = $contributor['display_name'] ?? '';
    $contributions = $contributor['contributions'] ?? null;
    $note = $contributor['note'] ?? null;

    $tag = $url ? 'a' : 'div';
    $classes = 'flex flex-col items-center gap-2 p-4 rounded-lg bg-base-200 text-center'
        . ($url ? ' hover:bg-base-300 transition-colors' : '');
@endphp

<{{ $tag }}
    @if($url) href="{{ $url }}" target="_blank" @endif
    class="{{ $classes }}"
>
    @if($login)
        <img src="https://github.com/{{ $login }}.png" alt="{{ $name }}" class="w-16 h-16 rounded-full">
    @else
        <div class="w-16 h-16 rounded-full bg-base-300 flex items-center justify-center text-2xl font-bold text-base-content/50">
            {{ strtoupper(substr($name, 0, 1)) }}
        </div>
    @endif

    <span class="font-medium text-sm">{{ $name }}</span>

    @if($contributions)
        <span class="text-xs text-base-content/60">{{ $contributions }} {{ Str::plural('commit', $contributions) }}</span>
    @endif

    @if($note)
        <span class="text-xs text-base-content/60">{{ $note }}</span>
    @endif
</{{ $tag }}>
