<div>
    <p class='text-3xl w-full text-center font-normal'>
        {{ sprintf('%02d:%02d',
            $timeInterval->h + ($timeInterval->d * 24) + ($timeInterval->m * 30 * 24) + ($timeInterval->y * 365 * 24),
            $timeInterval->i,
        ) }}
    </p>
    <h3 class='text-xl mb-2'>{{ $label }}</h3>
</div>