@extends('layouts.admin')

@section('title', 'Audit Log')

@section('body')
    <x-search/>
    <table class="table table-zebra">
        <thead>
        <tr>
            <th>Subject</th>
            <th>Causer</th>
            <th>Description</th>
            <th>Properties</th>
            <th>Time</th>
        </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
                <tr>
                    @if (is_null($log->subject))
                        <td>None</td>
                    @else
                        <td>{{$log->subject->first_name.' '.$log->subject->last_name}} ({{$log->subject->id}})</td>
                    @endif
                    @if (is_null($log->causer))
                        <td>None</td>
                    @else
                        <td>{{$log->causer->first_name.' '.$log->causer->last_name}} ({{$log->causer->id}})</td>
                    @endif
                    <td>{{$log->description}}</td>
                    <td>{{$log->properties}}</td>
                    <td>{{$log->created_at}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{$logs->links()}}
@endsection
