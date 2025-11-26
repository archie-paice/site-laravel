@extends('layouts.admin')

@section('title', 'Position Preset Management')

@section('body')
    <button class="btn btn-primary">
        <a href={{ route('position-presets.create') }} class='text-base-content no-underline'>+ New Preset</a>
    </button>
    @livewire('position-preset-table', ['positions' => $positions])
@endsection
