@extends('layouts.main')

@section('body')
    <h1>freaky ahh session</h1>

    <a class='btn btn-primary' href={{ route('auth.redirect') }}>
        Login With VATSIM
    </a>
@endsection