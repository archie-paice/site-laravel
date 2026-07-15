@props(['user'])

<img src="{{ asset($user->profile_image_route) }}" alt="{{ $user->name }}"
    {{ $attributes->merge(['class' => 'rounded-full object-cover border border-base-300 shrink-0']) }} />
