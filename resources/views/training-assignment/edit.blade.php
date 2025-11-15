@extends('layouts.admin')

@section('title', 'Edit Training Assignment - #'.$assignment->id)

@section('body')
    <div class="card card-body bg-base-300 max-w-100">
        <form
            action="{{route('training-assignments.update', ['assignment' => $assignment->id])}}"
            method="POST"
        >
            @csrf
            @method("PUT")

            <x-label label="Student" :value="$assignment->trainee->first_name.' '.$assignment->trainee->last_name"/>
            <x-label label="Requested Training" :value="$assignment->training_type"/>

            <form action="">
                <div class="flex flex-col mb-5">
                    <label class='label'>Instructor</label>
                    <select
                        class='input'
                        name="instructorId"
                    >
                        <option value="">None</option>
                        @foreach($instructors as $instructor)
                            <option
                                @if($instructor->user_id == $assignment->instructor_id)
                                    selected
                                @endif
                                value="{{$instructor->user_id}}"
                            >
                                {{$instructor->user->first_name.' '.$instructor->user->last_name}} ({{$instructor->user->id}})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col mb-5">
                    <label for="active" class="label">Active</label>
                    <input
                        name="active"
                        type="checkbox"
                        class="checkbox checkbox-primary"
                        @if($assignment->active)
                            checked
                        @endif
                        value="1"/>
                </div>

                <div class="flex flex-col mb-5">
                    <label for="created_at" class="label">Created At</label>
                    <input type="text" readonly class="input" value="{{$assignment->created_at}}">
                </div>

                <div class="flex flex-col mb-5">
                    <label for="updated_at" class="label">Last Updated</label>
                    <input type="text" readonly class="input" value="{{$assignment->updated_at}}">
                </div>

                <div class="card-actions">
                    <button
                        class="btn btn-primary"
                        type="submit"
                    >
                        Submit Changes
                    </button>
                </div>
            </form>
            add a dropdown for status (active, solo, mock, checkout, complete, forfeit)
        </form>
    </div>
@endsection
