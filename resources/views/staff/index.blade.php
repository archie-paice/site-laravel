
@extends('layouts.main')

@section('title', 'ARTCC Staff')

@section('body')
    <x-card-component title="ATM">
        {{ $staff }}
    </x-card-component>
@endsection
