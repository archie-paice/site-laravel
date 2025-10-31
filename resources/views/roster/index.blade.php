@extends('layouts.main')

@section('body')
    @unless(sizeof($users) == 0)
        <table class='table table-zebra table-md w-max border-2 border-base-300'>
            <thead>
                <tr class='text-xl font-bold'>
                    <th colspan='4'>ZJX Roster</th>
                    <th rowspan='1'>Certifications</th>
                </tr>
                <tr colspan='4'>
                    <th>CID</th>
                    <th>Name</th>
                    <th>Rating</th>
                    <th>Facility</th>
                    <th>Cert 1</th>
                    <th>Cert 2</th>
                    <th>Cert 3</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td class='border-r-1 border-base-300'>
                            <a href={{ route('users.show', ['user' => $user->id]) }} class='text-base-content no-underline'>{{ $user->id }}</a>
                        </td>
                        <td class='border-r-1 border-base-300'>
                            <a href={{ route('users.show', ['user' => $user->id]) }} class='text-base-content no-underline'>
                                {{ $user->last_name }}, {{ $user->first_name }}
                            </a>

                            @unless(strcasecmp($user->facility, env('VATUSA_FACILITY')) == 0)
                                <h3 class='badge badge-info badge-sm ml-2'>{{ $user->facility }} Visitor</h3>
                            @endunless
                        </td>
                        <td class='border-r-1 border-base-300'>{{ $user->rating->mapToString() }}</td>
                        <td class='border-r-1 border-base-300'>{{ $user->facility }}</td>
                        <td class='border-r-1 border-base-300'>TBD</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <h1>There are no rostered users.</h1>
    @endunless
@endsection