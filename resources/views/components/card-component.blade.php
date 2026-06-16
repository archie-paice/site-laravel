<div {{ $attributes->merge(['class' => 'card border-1 border-base-300 p-5']) }}>
    @unless(is_null($title ?? null))
        <h1 class="card-title text-lg font-bold leading-snug">{{ $title }}</h1>
    @endunless

    <div @class('card-body p-0')>
        {{  $slot }}
    </div>
</div>
