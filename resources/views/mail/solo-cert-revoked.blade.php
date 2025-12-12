@extends('mail.layout')

@section('content')
    <p>Hello {{ $soloCert->user->first_name }},</p>
    
    <p>Your solo certification for <strong>{{ $soloCert->position }}</strong> has been revoked. You are no longer authorized to control <strong>{{ $soloCert->position }}</strong> without an instructor present.</p>

    <p>Please contact your instructor for more information.</p>
@endsection