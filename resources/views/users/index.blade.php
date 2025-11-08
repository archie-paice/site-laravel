@extends('layouts.main')

@section('title', 'Profile')

@section('body')
    <div class="tabs tabs-box">
        <input type="radio" name="my_tabs_6" class="tab" aria-label="User Info"  checked="checked"/>
        <div class="tab-content bg-base-100 border-base-300 p-6">
            <div class='card w-max p-5'>
                <x-user-data :user='$user'/>
            </div>
        </div>

        <input type="radio" name="my_tabs_6" class="tab" aria-label="Training Sessions"/>
        <div class="tab-content bg-base-100 border-base-300 p-6">show the training sessions here</div>

        <input type="radio" name="my_tabs_6" class="tab" aria-label="Training Requests" />
        <div class="tab-content bg-base-100 border-base-300 p-6">
            @if(is_null($trainingAssignments) || !$trainingAssignments->first()->active)
                <strong>You don't have an active training request. Request training here.</strong>

                <form action="{{route('training-assignment.create')}}"
                      method="POST"
                      class="flex flex-col w-max mt-5"
                >
                    @csrf
                    <label for="trainingType" class="label">Training Type</label>
                    <select name="trainingType" class="select">
                        <option value="S1">S1</option>
                        <option value="S2">S2</option>
                        <option value="S3">S3</option>
                        <option value="C1">C1</option>
                        <option value="MCO GND">MCO GND</option>
                        <option value="MCO TWR">MCO TWR</option>
                        <option value="F11 TRACON">F11 TRACON</option>
                    </select>

                    <button type="submit" class="btn btn-primary mt-5">Request Training</button>
                </form>
            @endif

            @unless (count($trainingAssignments) == 0)

            @endunless
        </div>
    </div>
@endsection
