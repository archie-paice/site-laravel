@extends('layouts.admin')

@section('title', 'Certification Facilities')

@section('body')
    <table class="table table-zebra">
        <thead>
            <tr>
                <th>Name</th>
                <th>Identifier</th>
                <th>Actions</th>
            </tr>
            @foreach ($certificationFacilities as $facility)
                <tr>
                    <td>
                        <a class='link link-primary' href="{{ route('certification-facilities.show', $facility->id) }}">{{ $facility->name }}</a>
                    </td>
                    <td>{{ $facility->identifier }}</td>
                    <td>
                        <form method="POST" action="{{ route('certification-facilities.destroy', $facility->id) }}"
                              onsubmit="return confirm('Are you sure you want to delete this facility? This will delete all records, levels, and related certifications for all users, and is irreversible.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-error">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach

            @if(count($certificationFacilities) == 0)
                <tr>
                    <td colspan="3">No certification facilities defined.</td>
                </tr>
            @endif
        </thead>
    </table>

    <form method="POST" action="{{ route('certification-facilities.store') }}" class="mt-10 border-t-1 border-base-300 pt-5">
        @csrf
        <div class="flex flex-col gap-2">
            <label class="label" for="name">Facility Name</label>
            <input type="text" class="input" id="name" name="name" required>
        </div>

        <div class="flex flex-col gap-2">
            <label class="label" for="identifier">Identifier</label>
            <input type="text" class="input" id="identifier" name="identifier" required>
        </div>
        <button type="submit" class="btn btn-primary mt-2">Add Facility</button>
    </form>
@endsection
