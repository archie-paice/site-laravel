@extends('layouts.admin')

@section('title', 'Certification Facility - ' . $facility->name)

@section('body')
    <div class='grid lg:grid-cols-2 md:grid-cols-1 grid-cols-1 gap-5'>
        <x-card-component title='Facility Information'>

            <table class='table table-compact w-full mt-2'>
                <tr>
                    <th>Name:</th>
                    <td>{{ $facility->name }}</td>
                </tr>
                <tr>
                    <th>Identifier:</th>
                    <td>{{ $facility->identifier }}</td>
                </tr>
            </table>
            
            @livewire('certification-levels-table', ['facilityId' => $facility->id], key($facility->id))

            <form 
            action="{{ route('certification-levels.store', $facility->id) }}" 
            method="post"
            class='flex flex-col gap-2 w-max'>
                <h2 class='text-xl'>Add Certification Level</h2>
                @csrf
                <div>
                    <label for="name" class='label'>Name</label>
                    <br>
                    <input type="text" name="name" id="name" class="input input-sm">
                </div>

                <div>
                    <label for="abbreviation" class='label'>Abbreviation</label>
                    <br>
                    <input type="text" name="abbreviation" id="abbreviation" maxlength="3" class="input input-sm">
                </div>

                <div>
                    <label for="level" class='label'>Level</label>
                    <br>
                    <input type="number" name="level" id="level" class="input input-sm">
                </div>

                <button type="submit" class="btn btn-primary">Add Level</button>
            </form>
        </x-card-component>
    </div>

@endsection