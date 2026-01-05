@extends('mail.layout')

@section('content')
    <p>Hello {{ $visitorRequest->user->first_name }},</p>
    
    <p>Your visiting request to the Virtual Jacksonville ARTCC has been accepted. You can view your profile on the website using <a style='color: blue; text-decoration: underline;' href="{{ route('users.show', $visitorRequest->user) }}">this link.</a></p>

    <p>Your operating initials have been assigned as <strong>{{ $visitorRequest->operatingInitials }}</strong>.</p>

    <p>Please review the general operation, facility admin, and training policies prior to controlling. Welcome to the ARTCC!</p>
@endsection