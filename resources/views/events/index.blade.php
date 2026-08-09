@extends('layouts.main')

@section('title', 'Events')

@section('body')
    <div class="max-w-5xl mx-auto">
        <livewire:events-calendar/>
    </div>

    <script>
        document.querySelectorAll('[data-carousel-nav]').forEach((link) => {
            link.addEventListener('click', (e) => {
                const target = document.querySelector(link.getAttribute('href'));
                if (!target) return;
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'start' });
            });
        });
    </script>
@endsection
