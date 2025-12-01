@extends('layouts.main')

@section('title', 'Profile - '.$user->first_name.' '.$user->last_name)

@section('body')
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-2">
        <div>
            <h2 class="text-xl">General Info</h2>
            <x-user-data :user='$user'/>
        </div>

        @hasrole('training')
        <div>
            <h2 class="text-xl">Training</h2>
            <div class="tabs tabs-box">
                <input type="radio" name="my_tabs_6" class="tab" aria-label="Training Tickets" checked/>
                <div class="tab-content bg-base-100 border-base-300 p-6">
                    <x-label label="" value=""/>
                </div>

                <input type="radio" name="my_tabs_6" class="tab" aria-label="Training Assignments"/>
                <div class="tab-content bg-base-100 border-base-300 p-2">
                    @unless(sizeof($trainingAssignments) == 0)
                        <table class='table table-zebra table-md w-max border-2 border-base-300 mt-5'>
                            <thead>
                            <tr>
                                <th>Instructor</th>
                                <th>Training Requested</th>
                                <th>Requested At</th>
                                <th>Last Session</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($trainingAssignments as $trainingAssignment)
                                <tr>
                                    @if(is_null($trainingAssignment->instructor))
                                        <td>None</td>
                                    @else
                                        <td>
                                            <a href="{{route('users.show', ['user' => $trainingAssignment->instructor_id])}}">
                                                {{$trainingAssignment->instructor->name}}
                                            </a>
                                        </td>
                                    @endif

                                    <td>{{$trainingAssignment->training_type}}</td>
                                    <td>{{(new DateTime($trainingAssignment->created_at))->format("m-d-y, h:m A")}}</td>
                                    <td>
                                        @if(count($trainingAssignment->student->trainingTicketsAsStudent) == 0)
                                            None Logged
                                        @else
                                            {{(new DateTime($trainingAssignment->student->trainingTicketsAsStudent->first()->session_start))->format("m-d-y, h:m A")}}
                                        @endif
                                    </td>

                                    <td>
                                        <x-training-assignment-status-label :status="$trainingAssignment->status"/>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <h1>There are no training assignments.</h1>
                    @endunless
                </div>

                <input type="radio" name="my_tabs_6" class="tab" aria-label="Solo Certs"/>
                <div class="tab-content bg-base-100 border-base-300 p-6">

                </div>

            </div>
        </div>
        @endhasrole
    </div>
@endsection
