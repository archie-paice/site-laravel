@extends('layouts.main')

@section('body')
    <div class="flex flex-col justify-center items-center gap-6">
        <div class="card card-dash bg-base-100 w-xl shadow-sm">
            <figure>
                <img src="https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp" alt="Shoes" />
            </figure>
            <div class="card-body bg-neutral">
                <h1 class="card-title">
                    {{ $event->name }}
                    <div class="badge badge-secondary">{{ $event->type }}</div>
                </h1>
                <h2>
                    {{ $event->start }} - {{ $event->end }}
                </h2>
                @if ($event->featured_fields)
                    <p>{{ implode(', ', $event->featured_fields) }}</p>
                @else
                    <p>No fields</p>
                @endif
                <br />
                <p>{{ $event->description }}</p>
            </div>
        </div>

        <div class="card bg-base-100 w-xl shadow-sm">
            <div class="card-body bg-neutral">
                <h2 class="card-title">Request Position</h2>
                <p>A card component has a figure, a body part, and inside body there are title and actions parts</p>
                <div class="card-actions justify-end">
                    <button class="btn btn-primary">Buy Now</button>
                </div>
            </div>
        </div>
    </div>
@endsection
