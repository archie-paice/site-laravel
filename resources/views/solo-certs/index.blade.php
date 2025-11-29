@extends('layouts.admin')

@section('title', 'Solo Certs')

@section('body')
    <a href="{{route('solo-certs.create')}}" class="btn btn-primary mb-2">Create a Solo Cert</a>

    <x-search/>

    <table class="table table-zebra mt-2">
        <thead>
        <tr>
            <th>User</th>
            <th>Issued By</th>
            <th>Created At</th>
            <th>Expires On</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @if(count($soloCerts) == 0)
            <tr>
                <td colspan="5" class="text-xl">No Solo Cert Data to Display</td>
            </tr>
        @endif
        @foreach($soloCerts as $soloCert)
            <tr>
                <td>
                    <a href="{{route('users.show', ['user' => $soloCert->user])}}">
                        {{$soloCert->user->nameReversed}} ({{$soloCert->user->id}})
                    </a>
                </td>
                <td>
                    <a href="{{route('users.show', ['user' => $soloCert->issuedBy])}}">
                        {{$soloCert->issuedBy->nameReversed}} ({{$soloCert->issuedBy->id}})
                    </a>
                </td>
                <td>{{$soloCert->created_at->format('Y-m-d')}}</td>
                <td>{{$soloCert->expires->format('Y-m-d')}}</td>
                @if($soloCert->expires < new DateTime())
                    <td class="badge badge-error text-error-content">Expired</td>
                @else
                    <td class="badge badge-success text-success-content">Active</td>
                @endif
                <td>actions</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
