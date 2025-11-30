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
                <td>
                    @if ($soloCert->revoked)
                        <div class="badge badge-error text-error-content">Revoked</div>
                    @elseif($soloCert->expired)
                        <div class="badge badge-warning text-error-content">Expired</div>
                    @else
                        <div class="badge badge-success text-success-content">Active</div>
                    @endif
                </td>
                <td>
                    <ul class='text-accent menu menu-horizontal h-10 items-center gap-x-5 justify-center'>
                        <li>
                            <details>
                                <summary>Actions</summary>
                                <ul class="bg-base-100 text-base-content rounded-t-none p-2 z-10">
                                    @if (!$soloCert->expired && !$soloCert->revoked)
                                        @haspermission('revoke solo certs')
                                            <li class="w-max">
                                                <form method="POST" action="{{route('solo-certs.destroy', ['solo_cert' => $soloCert])}}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="w-full text-left">Revoke Solo Cert</button>
                                                </form>
                                            </li>
                                        @endhaspermission
                                    @endif
                                </ul>
                            </details>
                        </li>
                    </ul>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
