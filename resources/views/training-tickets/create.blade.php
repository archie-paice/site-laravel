

@extends('layouts.admin')

@section('title', 'Create Training Ticket')

@section('body')
    <div class="card card-body bg-base-300 max-w-150">
        <form
            class="flex flex-col"
            action="{{route('training-tickets.store')}}"
            method="POST"
        >
            @csrf

            <label for="student" class="label">Student</label>
            <select
                name="student"
                id=""
                class="select"
                required
            >
                <option value="">Select a Student</option>

                @foreach($users as $user)
                    <option value="{{$user->id}}">{{$user->nameReversed}} ({{$user->id}})</option>
                @endforeach
            </select>

            <br>

            <label for="position" class="label">Position (must be in XXX_XXX form)</label>
            <input
                type="text"
                name="position"
                class="input"
                oninput="this.value = this.value.toUpperCase();"
                pattern="^([A-Z]{2,3})(_([A-Z]{1,3}))?_(DEL|GND|TWR|APP|DEP|CTR)$"
                required
            >

            <br>

            <label for="location" class="label">Session Location</label>
            <select
                name="location"
                id=""
                class="select"
                required
            >
                <option value="">Select a Location</option>
                <option value="0">Classroom</option>
                <option value="1">Live Network</option>
                <option value="2">Sweatbox</option>
            </select>

            <br>

            <label for="sessionStart" class="label">Start Date</label>
            <input
                type="datetime-local"
                name="sessionStart"
                class="input"
                required
            >

            <br>

            <label for="sessionEnd" class="label">End Date</label>
            <input
                type="datetime-local"
                name="sessionEnd"
                class="input"
                required
            >

            <br>

            <label for="movements" class="label">Number of Movements</label>
            <input
                name="movements"
                type="number"
                class="input"
                value="0"
                required
            >

            <br>

            <label for="score" class="label">Score</label>
            <div class="rating">
                <input type="radio" name="score" value="1" class="mask mask-star" aria-label="1 star" />
                <input type="radio" name="score" value="2" class="mask mask-star" aria-label="2 star" />
                <input type="radio" name="score" value="3" class="mask mask-star" aria-label="3 star" />
                <input type="radio" name="score" value="4" class="mask mask-star" aria-label="4 star" checked />
                <input type="radio" name="score" value="5" class="mask mask-star" aria-label="5 star" />
            </div>

            <br>

            <label for="notes" class="label">Notes</label>
            <textarea
                name="notes"
                id="notes"
                class="textarea"
                minlength="20"
                maxlength="2048">

            </textarea>

            <div class="card-actions mt-5">
                <button
                    class="btn btn-primary"
                    type="submit"
                >Submit</button>
            </div>
        </form>
    </div>
@endsection


