@extends('layouts.admin')

@section('title', 'Edit Training Assignment - #'.$assignment->id)

@section('body')
    <div class="card card-body bg-base-300 max-w-100">
        <form
            action=""
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
                        name="instructoId"
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
                    <input type="checkbox" class="checkbox checkbox-primary">
                </div>

                <div class="flex flex-col mb-5">
                    <label for="active" class="label">Created At</label>
                    <input type="text" readonly class="input" value="{{$assignment->created_at}}">
                </div>

                <div class="flex flex-col mb-5">
                    <label for="active" class="label">Last Updated</label>
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
        </form>
    </div>
@endsection
