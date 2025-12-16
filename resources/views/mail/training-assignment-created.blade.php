@extends('mail.layout')

@section('content')
    <p>Hello {{ $trainingAssignment->student->first_name }},</p>
    
    <p>Your training assignment for {{ $trainingAssignment->training_type->mapToString() }} has been recieved.</p>

    <p>To view your position in the training queue and other relevant information, please visit your <a href="{{ route('users.show.training-assignments', $trainingAssignment->student) }}" style='color: blue; text-decoration: underline;'>profile</a>.</p>
@endsection