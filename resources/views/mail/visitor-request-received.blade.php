@extends('mail.layout')

@section('content')
    <p>Hello {{ $visitorRequest->user->first_name }},</p>
    
    <p>Your visiting request to the Virtual Jacksonville ARTCC has been received. You can monitor the status of your visiting request at <a style='color: blue; text-decoration: underline;' href="{{ route('visit.index') }}">this link.</a></p>

    <p>Please allow up to 5 business days for review.</p>
@endsection