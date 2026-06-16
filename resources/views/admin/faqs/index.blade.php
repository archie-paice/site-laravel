@extends('layouts.admin')

@section('title', 'Manage FAQs')

@section('body')
    <x-card-component title="FAQ Management">
        @livewire('manage-faqs')
    </x-card-component>
@endsection
