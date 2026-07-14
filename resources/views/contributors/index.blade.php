@extends('layouts.main')

@section('title', 'Contributors')

@section('body')
    <div class="w-full px-4 sm:px-6 lg:px-8 flex flex-col gap-6">

        <p class="text-base-content/70 -mt-3">
            Full commit history can be found on
            <a href="https://github.com/zjx-artcc/site-laravel" target="_blank" class="link link-primary">our public GitHub.</a>
        </p>

        <x-contributor-section title="Main Contributors" :contributors="$main"
                               empty-message="No contributors found." />

        <x-contributor-section title="ZJX Fork Contributors" :contributors="$fork" />

        <x-contributor-section title="Contributors" :contributors="$contributor" />

        <x-contributor-section title="Beta Testers" :contributors="$beta" />

    </div>
@endsection
