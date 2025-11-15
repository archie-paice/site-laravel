@extends('layouts.admin')

@section('title', 'Training Tickets')

@section('body')
    <a href="{{route('training-tickets.create')}}" class="btn btn-primary">Create a Training Ticket</a>
    <table class="table table-zebra">
        <thead>
            <tr>
                <th>Student</th>
                <th>Instructor</th>
                <th>Position</th>
                <th>Progress Rating</th>
                <th>Session Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @if(count($trainingTickets) == 0)
                <tr>
                    <td colspan="5" class="text-xl">No Training Data to Display</td>
                </tr>
            @endif
            @foreach($trainingTickets as $trainingTicket)
                <tr>
                    <td>{{$trainingTicket->student->first_name.' '.$trainingTicket->student->last_name}}</td>
                    <td>{{$trainingTicket->instructor->first_name.' '.$trainingTicket->instructor->last_name}}</td>
                    <td>{{$trainingTicket->position}}</td>
                    <td>{{$trainingTicket->score}}</td>
                    <td>{{$trainingTicket->session_start}}</td>
                    <td>
                        <a href="{{route('training-tickets.show', ['ticket' => $trainingTicket->id])}}">
                            View
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
