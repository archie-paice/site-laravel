<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title') - ZJX ARTCC</title>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/htmx.org@2.0.8/dist/htmx.min.js"></script>
        @livewireStyles
        @livewireScripts

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class='min-h-dvh' data-theme='light'>
        <x-navbar/>

        @if(session('error'))
            <div x-data="{open: true}" x-show='open' class="alert alert-error alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class='btn btn-ghost cursor-pointer' x-on:click='open = false'>Close</button>
            </div>
        @endif

        @if(session('success'))
            <div x-data="{open: true}" x-show='open' class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class='btn btn-ghost cursor-pointer' x-on:click='open = false'>Close</button>
            </div>
        @endif

        @yield('body-nopad')

        <h1 class='font-bold text-2xl ml-5 mt-5'>@yield('title')</h1>

        <div class="p-5">
            @yield('body')
        </div>
    </body>
</html>
