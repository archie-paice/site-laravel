@extends('layouts.admin')

@section('title', 'New Event Position Preset')

@section('body')
    <div class="card card-body bg-base-300">
        <form method="POST" action="{{ route('position-presets.store') }}" class="flex flex-col">
            @csrf
            <label for="positions" class="label">Preset Name</label>
            <input name="name" type="text" required placeholder="Eg. Generic Positions by Rating" class="input" />
            <br/>

            <label for="positions" class="label">Positions (type and separate each position with a comma)</label>
            <input name="positions" type="text" required placeholder="Eg. MCO_GND, MCO_TWR" class="input" />

            <button class="btn" type="submit">Create Preset</button>
        </form>
    </div>

@endsection
