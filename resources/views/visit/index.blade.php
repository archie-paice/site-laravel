@extends('layouts.main')

@section('title', 'Visit the Virtual Jacksonville ARTCC')

@section('body')
        <p class="mb-4">Welcome to the Virtual Jacksonville ARTCC! We are excited to offer visiting controllers the opportunity to experience our facility and contribute to our operations.</p>
        <h2 class="text-2xl font-semibold mb-4">How to Visit</h2>
        <ol class="list-decimal list-inside mb-4">
            <li class="mb-2">Be a member of or join the <a class='link link-primary' href="https://www.vatusa.net/help/kb#q12">VATUSA division</a>.</li>
            <li class="mb-2">Submit a visiting request and ensure you meet the eligibility requirements on the next page.</li>
            <li class="mb-2">Wait 1-2 business days and enjoy controlling!</li>
        </ol>

        @if (auth()->user() && !auth()->user()->rostered)
            <a href="{{ route('visit.create') }}" class="btn btn-primary">Submit a Visiting Request</a>       
        @elseif(auth()->user() && auth()->user()->rostered)
            <p class="btn btn-disabled">You are already rostered at vZJX. No need to submit a visiting request!</p>
        @else
            <a class='btn btn-primary' href="{{ route('auth.redirect') }}">Log in to Submit a Visiting Request</a>
        @endif
@endsection