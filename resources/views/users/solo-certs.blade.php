@extends('layouts.profile')

@section('profile-content')
        <x-solo-certs-table-user-profile :soloCerts="$soloCerts"/>
@endsection