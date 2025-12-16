@extends('mail.layout')

@section('content')
    <p>Hello {{ $soloCert->user->first_name }},</p>
    
    <p>A solo certification has been issued by <strong>{{ $soloCert->issuedBy->name }}</strong> for <strong>{{ $soloCert->position }}</strong>. You can view it on the website using <a style='color: blue; text-decoration: underline;' href="{{ route('users.show.solo-certs', $soloCert) }}">this link.</a></p>

    <p>This solo cert is valid for 30 days. It expires on {{ $soloCert->expires->format('Y-m-d') }}. Please review the training SOP regarding the restrictions related to solo certifications and contact your instructor if you have any questions.</p>
@endsection