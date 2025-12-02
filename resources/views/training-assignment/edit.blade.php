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

            <x-label label="Student" :value="sprintf('%s (%s)', $assignment->student->name, $assignment->student->id)"/>


            <div class="flex flex-col mb-5">
                <label for="trainingType" class="label">Training Type</label>
                <select name="trainingType" id="trainingType" class="select">
                    @foreach(\App\Enums\TrainingType::cases() as $trainingType)
                        <option
                            value="{{$trainingType}}"
                            @selected(old('trainingType') ?? $trainingType == $assignment->training_type)
                        >
                            {{$trainingType->mapToString()}}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-col mb-5">
                <label class='label'>Instructor</label>
                <select
                    class='select'
                    name="instructorId"
                >
                    <option value="">None</option>
                    @foreach($instructors as $instructor)
                        <option
                            @selected(old('instructor') ?? $instructor->user_id == $assignment->instructor_id)

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

            <div class="mb-6">
                <label for="status" class="label">Status</label>
                <select name="status" id="" class="select">
                    @foreach(\App\Enums\TrainingStatus::cases() as $status)
                        <option value="{{$status}}" @selected(old('status') ?? $assignment->status == $status)>{{ $status->label() }}</option>
                    @endforeach
                </select>
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
    </div>
@endsection
