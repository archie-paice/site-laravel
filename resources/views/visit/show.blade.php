@extends('layouts.admin')
@section('title', 'Visitor Request - #'.$request->id)

@section('body')
    <x-card-component>
        <div class='flex flex-col'>
            <a class='text-xl font-bold' href="{{ route('users.show', [$request->user->id]) }}">{{ $request->user->name }}</a>
            <a class='link link-primary' target='_blank' href="https://www.vatusa.net/mgt/controller/{{ $request->user->id }}">View VATUSA History</a>
            <br>

            <label for="status" class='label'>Status</label>
            @if ($request->approved)
                <span class="badge badge-success">Approved</span>
            @else
                <span class="badge badge-warning">Pending</span>
            @endif
            <br>
            <label for="submitted" class='label'>Date Submitted</label>
            <p>{{ $request->created_at->format('Y-m-d') }}</p>
            <br>
            <label for="reason" class='label'>Reason for Visit</label>
            <p>{{ $request->user_note }}</p>

            <br>

            <label for="operatingInitials" class='label'>Operating Initials (if applicable)</label>
            <input class='input' type="text" name="operatingInitials" id="" value='{{ old('operatingInitials', $request->user->operatingInitials) }}'>

            <br>

            <label for="reason" class='label'>Reason For Denial (if applicable)</label>
            <input class='input' type="text" name="reason" id="" value='{{ old('reason', $request->reason) }}'>

            <br>

            <label for="admin_notes" class='label'>Admin Notes</label>
            <input class='input' type="text" name="admin_notes" id="" value='{{ old('admin_notes', $request->admin_notes) }}'>
            
        </div>
    </x-card-component>
@endsection