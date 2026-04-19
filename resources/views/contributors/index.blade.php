@extends('layouts.main')

@section('title', 'Credit Information')

@php
    function contributorCard(array $c): void { ?>
        <?php if($c['html_url']): ?>
            <a href="<?= e($c['html_url']) ?>" target="_blank"
               class="flex flex-col items-center gap-2 p-4 rounded-lg bg-base-200 hover:bg-base-300 transition-colors text-center">
        <?php else: ?>
            <div class="flex flex-col items-center gap-2 p-4 rounded-lg bg-base-200 text-center">
        <?php endif; ?>
            <?php if($c['login']): ?>
                <img src="https://github.com/<?= e($c['login']) ?>.png" alt="<?= e($c['display_name']) ?>" class="w-16 h-16 rounded-full">
            <?php else: ?>
                <div class="w-16 h-16 rounded-full bg-base-300 flex items-center justify-center text-2xl font-bold text-base-content/50">
                    <?= e(strtoupper(substr($c['display_name'], 0, 1))) ?>
                </div>
            <?php endif; ?>
            <span class="font-medium text-sm"><?= e($c['display_name']) ?></span>
            <?php if(!empty($c['contributions'])): ?>
                <span class="text-xs text-base-content/60"><?= e($c['contributions']) ?> commit<?= $c['contributions'] !== 1 ? 's' : '' ?></span>
            <?php endif; ?>
            <?php if(!empty($c['note'])): ?>
                <span class="text-xs text-base-content/60"><?= e($c['note']) ?></span>
            <?php endif; ?>
        <?php if($c['html_url']): ?>
            </a>
        <?php else: ?>
            </div>
        <?php endif;
    }
@endphp

@section('body')
    <div class="w-full px-4 sm:px-6 lg:px-8 flex flex-col gap-6">

        <p class="text-base-content/70 -mt-3">
            Full commit history can be found on
            <a href="https://github.com/zjx-artcc/site-laravel" target="_blank" class="link link-primary">our public GitHub.</a>
        </p>

        {{-- Main Contributors --}}
        <x-card-component title="Main Contributors">
            @if($main->isEmpty())
                <p class="mt-4 text-base-content/60">No contributors found.</p>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4 mt-4">
                    @foreach($main as $c)
                        <?php contributorCard($c); ?>
                    @endforeach
                </div>
            @endif
        </x-card-component>

        {{-- Fork Contributors --}}
        @if($fork->isNotEmpty())
            <x-card-component title="ZJX Fork Contributors">
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4 mt-4">
                    @foreach($fork as $c)
                        <?php contributorCard($c); ?>
                    @endforeach
                </div>
            </x-card-component>
        @endif

        {{-- Contributors --}}
        @if($contributor->isNotEmpty())
            <x-card-component title="Contributors">
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4 mt-4">
                    @foreach($contributor as $c)
                        <?php contributorCard($c); ?>
                    @endforeach
                </div>
            </x-card-component>
        @endif

        {{-- Beta Testers --}}
        @if($beta->isNotEmpty())
            <x-card-component title="Beta Testers">
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4 mt-4">
                    @foreach($beta as $c)
                        <?php contributorCard($c); ?>
                    @endforeach
                </div>
            </x-card-component>
        @endif

    </div>
@endsection
