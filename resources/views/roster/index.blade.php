@extends('layouts.main')

@section('body')
    <table class='border-2 w-150'>
        <thead class='border-2 border-black bg-accent text-accent-content'>
            <tr>
                <th>CID</th>
                <th>Name</th>
                <th>Rating</th>
                <th>Facility</th>
                <th>Certifications</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr class='odd:bg-base-300 even:bg-base-200'>
                    <td class='px-2 py-1 border-x-1'>
                        <a href={{ route('users.show', ['user' => $user->id]) }} class='text-base-content no-underline'>{{ $user->id }}</a>
                    </td>
                    <td class='px-2 py-1 border-x-1'>{{ $user->first_name }} {{ $user->last_name }}</td>
                    <td class='px-2 py-1 border-x-1 text-center'>{{ $user->rating->mapToString() }}</td>
                    <td class='px-2 py-1 border-x-1 text-center'>{{ $user->facility }}</td>
                    <td class='px-2 py-1 border-x-1 text-center'>TBD</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection