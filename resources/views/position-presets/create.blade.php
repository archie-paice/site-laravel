@extends('layouts.admin')

@section('title', 'New Event Position Preset')

@section('body')
        <form method="POST" action="{{ route('position-presets.store') }}" class="flex flex-col gap-2 w-max">
            @csrf
            <label for="positions" class="label">Preset Name</label>
            <input name="name" type="text" required placeholder="Eg. Generic Positions by Rating" class="input" />
            <br/>

            <label for="positions" class="label">Positions (type and separate each position with a comma)</label>
            <input name="positions" type="text" required placeholder="Eg. MCO_GND, MCO_TWR" class="input" />

            <button class="btn btn-primary" type="submit">Create Preset</button>
        </form>

@endsection
