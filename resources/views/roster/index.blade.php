@extends('layouts.main')

@section('title', 'Roster')
@section('body')
    @unless(sizeof($users) == 0)
        <table class='table table-zebra table-md w-max border-2 border-base-300'>
            <thead>
                <tr class='text-xl font-bold'>
                    <th colspan='3'>ZJX Roster</th>
                    <th colspan={{ count($certificationFacilities) }}>Certifications</th>
                </tr>
                <tr colspan='4'>
                    <th>CID</th>
                    <th>Name</th>
                    <th>Rating</th>
                    @foreach($certificationFacilities as $facility)
                        <th>{{ $facility->identifier }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td class='border-r-1 border-base-300'>
                            <a href='{{ route('users.show', ['user' => $user->id]) }}' class='text-base-content no-underline'>{{ $user->id }}</a>
                        </td>
                        <td class='border-r-1 border-base-300'>
                            <a href='{{route('users.show', ['user' => $user->id])}}' class='text-base-content no-underline'>
                                {{ $user->last_name }}, {{ $user->first_name }}
                            </a>

                            @unless(strcasecmp($user->facility, env('VATUSA_FACILITY')) == 0)
                                <h3 class='badge badge-error badge-sm ml-2'>{{ $user->facility }} Visitor</h3>
                            @endunless
                        </td>
                        <td class='border-r-1 border-base-300'>{{ $user->rating->mapToString() }}</td>
                            @foreach($certificationFacilities as $facility)
                                <td class='text-center'>
                                    @php
                                        $cert = $user->certifications->where('facility_id', $facility->id)->first();
                                    @endphp

                                    @if($cert)
                                        <span class='badge badge-success badge-md'>{{ $cert->level->identifier }}</span>
                                    @else
                                        <span class='text-gray-400'>Uncertified</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <h1>There are no rostered users.</h1>
    @endunless
@endsection
