@extends('mail.layout')

@section('content')
    <p>Hello {{ $assignment->student->first_name }},</p>
    

    <p>Your training assignment for {{ $assignment->training_type->mapToString() }} has been updated:</p>

    <p><strong>Instructor: </strong>
        @if ($assignment->instructor === null)
            Unassigned
        @else
            {{ $assignment->instructor->name }}
        @endif
    </p>
    <p><strong>Status: </strong>{{ $assignment->status->label() }}</p>

    @if ($assignment->instructor != null)
        <p>If you haven't done so already, please contact your instructor via the Discord Server within 7 days of this message, otherwise you risk removal from the training program. <strong>It is your responsibility to reach out to your instructor.</strong></p>
    @endif

    <p>For more information, please visit your <a href="{{ route('users.show', $assignment->student) }}" style='color: blue; text-decoration: underline;'>profile</a>.</p>
@endsection