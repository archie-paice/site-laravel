@extends('mail.layout')

@section('content')
    <p>Hello {{ $user->first_name }},</p>
    <p>Welcome to {{ config('app.vatusa_facility', 'ZJX') }}! We are happy to have you as part of the ARTCC.</p>

    <p>Please familiarize yourself with our code of conduct, training SOP, and visit your profile on the website when you are ready to make a training request.</p>
@endsection