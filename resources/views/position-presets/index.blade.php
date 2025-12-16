@extends('layouts.admin')

@section('title', 'Position Preset Management')

@section('body')
    <a href={{ route('position-presets.create') }} class='btn btn-primary mb-5'>+ New Preset</a>
    @livewire('position-preset-table', ['positions' => $positions])
@endsection
