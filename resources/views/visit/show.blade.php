@php
    use \App\Enums\VisitRequestStatus;
@endphp

@extends('layouts.admin')
@section('title', 'Visitor Request - #'.$request->id)

@section('body')
    <x-card-component>
        <div class='flex flex-col'>
            <a class='text-xl font-bold' href="{{ route('users.show', [$request->user->id]) }}">{{ $request->user->name }}</a>
            <a class='link link-primary' target='_blank' href="https://www.vatusa.net/mgt/controller/{{ $request->user->id }}">View VATUSA History</a>
            <br>

            <form method="POST" class='flex flex-col'>
                @csrf
                @method('PUT')

                <label for="status" class='label'>Status</label>
                @switch($request->status)
                    @case(VisitRequestStatus::PENDING)
                        <p class='badge badge-warning'>Pending</p>
                        @break
                    @case(VisitRequestStatus::APPROVED)
                        <p class='badge badge-success'>Approved</p>
                        @break
                    @case(VisitRequestStatus::DENIED)
                        <p class='badge badge-error'>Denied</p>
                        @break
                    @default
                        <p class='font-bold'>Unknown</p>
                @endswitch
                <br>
                <label for="submitted" class='label'>Date Submitted</label>
                <p>{{ $request->created_at->format('Y-m-d') }}</p>
                
                <br>

                <label for="reason" class='label'>Reason for Visit</label>
                <p>{{ $request->user_note }}</p>

                <br>

                <livewire:operating-initials-input :visitRequest="$request" />

                <br>

                <label for="reason" class='label'>Reason For Denial (if applicable)</label>
                <input 
                @disabled($request->status !== VisitRequestStatus::PENDING) 
                class='input' type="text" 
                name="reason" 
                id="" 
                value='{{ old('reason', $request->reason) }}'>

                <br>

                <label for="admin_notes" class='label'>Admin Notes</label>
                <input @disabled($request->status !== VisitRequestStatus::PENDING) class='input' type="text" name="admin_notes" id="" value='{{ old('admin_notes', $request->admin_notes) }}'>
                
                @if ($request->status == VisitRequestStatus::PENDING)
                    <div class='card-actions mt-5'>
                        <button type="submit" class='btn btn-success' formaction='{{ route('visit.approve', $request->id) }}'>Approve Request</button>
                        <button type="submit" class='btn btn-error' formaction='{{ route('visit.deny', $request->id) }}'>Deny Request</button>
                    </div>
                @endif
            </form>
        </div>
    </x-card-component>
@endsection