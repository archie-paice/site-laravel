@extends('layouts.admin')

@section('title', 'Edit Preset')

@section('body')
    <x-card-component>
        <form method="POST" action="{{ route('admin.events.position-presets.update', ['position_preset' => $position->id]) }}"
            class="flex flex-col">
            @csrf
            @method('PUT')

            <label for="positions" class="label">Preset Name</label>
            <input name="name" value="{{ old('name', $position->name) }}" type="text" required placeholder="Eg. Generic Positions by Rating" class="input" />
            <br />

            <label for="positions" class="label">Positions (type and separate each position with a comma)</label>
            <input name="positions" value="{{ old('positions', implode(', ', $position->positions ?? [])) }}" type="text" required placeholder="Eg. MCO_GND, MCO_TWR" class="input" />

            <button class="btn" type="submit">Update Preset</button>
        </form>
    </x-card-component>


@endsection
