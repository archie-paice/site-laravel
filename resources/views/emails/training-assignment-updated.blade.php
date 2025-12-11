@extends('layouts.email')

@section('content')
    <p>Hello {{ $assignment->student->first_name }},</p>
    

    <p>Your training assignment for {{ $assignment->training_type->mapToString() }} has been updated to <strong>{{ $assignment->status->label() }}</strong>.</p>

    <p>For more information, please visit your <a href="{{ route('users.show', $assignment->student) }}" style='color: blue; text-decoration: underline;'>profile</a>.</p>
@endsection