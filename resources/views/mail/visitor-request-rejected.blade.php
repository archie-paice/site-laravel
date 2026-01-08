@extends('mail.layout')

@section('content')
    <p>Hello {{ $visitorRequest->user->first_name }},</p>
    
    <p>Your visiting request to the Virtual Jacksonville ARTCC has been rejected.</p>

    <p>Reason for Rejection: <strong>{{ $visitorRequest->reason }}</strong>.</p>

    <p>If you wish to appeal this, please contact <strong><a href="mailto:atm@zjxartcc.org">atm@zjxartcc.org</a></strong></p>
@endsection