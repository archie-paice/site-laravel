@extends('layouts.admin')

@section('title', 'Create Training Ticket')

@section('body')
    'user_id',
    'instructor_id',
    'session_date',
    'duration',
    'movements',
    'score',
    'notes',
    'location',
    'ots_status',
    'solo_granted',
    'vatusa_id',
    'vatusa_synced'

    <div class="card card-body bg-base-300 max-w-100">
        <form class="flex flex-col">
            <label for="student" class="label">Student</label>
            <select
                name="student"
                id=""
                class="select"
            >
                <option value="">Select a Student</option>

                @foreach($users as $user)
                    <option value="{{$user->id}}">{{$user->name}} ({{$user->id}})</option>
                @endforeach
            </select>

            <br>

            <label for="sessionStartDate" class="label">Start Date</label>
            <input
                type="datetime-local"
                name="sessionStartDate"
                class="input"
            >

            <br>

            <label for="sessionEndDate" class="label">End Date</label>
            <input
                type="datetime-local"
                name="sessionEndDate"
                class="input"
            >

            <br>

            <label for="duration" class="label">Duration (HH:MM)</label>
            <input name="duration" type="number" class="input">

            <br>

            <label for="movements" class="label">Number of Movements</label>
            <input
                name="movements"
                type="number"
                class="input"
                value="0"
            >

            <br>

            <label for="score" class="label">Score</label>
            <div class="rating">
                <input type="radio" name="score" class="mask mask-star" aria-label="1 star" />
                <input type="radio" name="score" class="mask mask-star" aria-label="2 star" />
                <input type="radio" name="score" class="mask mask-star" aria-label="3 star" />
                <input type="radio" name="score" class="mask mask-star" aria-label="4 star" />
                <input type="radio" name="score" class="mask mask-star" aria-label="5 star" checkeds />
            </div>
        </form>
    </div>
@endsection
