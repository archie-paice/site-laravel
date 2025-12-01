@extends('layouts.main')

@section('title', 'Profile - '.auth()->user()->name)

@section('body')
    <dialog id="withdraw_modal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg">Confirm Action</h3>
            <p class="py-4">Are you sure you want to widthdraw this training request?</p>
            <div class="modal-action">
                <form action="{{route('training-assignments.destroy')}}" method="POST">
                    @method('DELETE')
                    @csrf
                    <input
                        class="hidden"
                        type="text"
                        name="id"
                        id="actionId"
                    />

                    <button
                        class="btn btn-error"
                        type="submit"
                    >
                        Withdraw
                    </button>

                    <button
                        class="btn"
                        type="button"
                        onclick="withdraw_modal.close()"
                    >
                        Close
                    </button>
                </form>
            </div>
        </div>
    </dialog>

    <div class="tabs tabs-box">
        <input type="radio" name="my_tabs_6" class="tab" aria-label="User Info" checked/>
        <div class="tab-content bg-base-100 border-base-300 p-6">
            <x-user-data :user='$user'/>
        </div>

        <input type="radio" name="my_tabs_6" class="tab" aria-label="Training Sessions"/>
        <div class="tab-content bg-base-100 border-base-300 p-6">
            <x-profile-training-tickets-table :training-tickets="$trainingTickets"/>
        </div>

        @if(Auth::user()->rostered)
            <input type="radio" name="my_tabs_6" class="tab" aria-label="Training Requests"/>
            <div class="tab-content bg-base-100 border-base-300 p-6">
                @if((is_null($trainingAssignments) || count($trainingAssignments) == 0 || !$trainingAssignments->first()->active))
                    <strong>You don't have an active training request. Request training here.</strong>

                    <form action="{{route('training-assignment.create')}}"
                          method="POST"
                          class="flex flex-col w-max mt-5"
                    >
                        @csrf
                        <label for="trainingType" class="label">Training Type</label>
                        <select name="trainingType" class="select">
                            @foreach(\App\Enums\TrainingType::cases() as $trainingType)
                                <option value="{{$trainingType}}">{{$trainingType->mapToString()}}</option>
                            @endforeach
                        </select>

                        <button type="submit" class="btn btn-primary mt-5">Request Training</button>
                    </form>
                @endif

                @unless (count($trainingAssignments) == 0)
                    <h2 class="font-bold text-2xl mt-5">Training Assignment History</h2>

                    <table class="table max-w-200">
                        <thead>
                        <tr>
                            <th>Training Type</th>
                            <th>Instructor</th>
                            <th>Updated At</th>
                            <th>Created At</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($trainingAssignments as $trainingAssignment)
                            <tr>
                                <td>{{$trainingAssignment->training_type->mapToString()}}</td>
                                @unless (is_null($trainingAssignment->instructor))
                                    <td>{{$trainingAssignment->instructor}}</td>
                                @else
                                    <td>Unassigned</td>
                                @endunless
                                <td>{{$trainingAssignment->updated_at}}</td>
                                <td>{{$trainingAssignment->created_at}}</td>
                                @if ($trainingAssignment->active)
                                    <td class="text-success">Active</td>
                                @else
                                    <td class="text-error">Inactive</td>
                                @endif
                                <td>
                                    @if ($trainingAssignment->active)
                                        <ul class='menu menu-horizontal items-center gap-x-5 justify-center'>
                                            <li>
                                                <details>
                                                    <summary>Actions</summary>
                                                    <ul class="bg-base-100 text-base-content rounded-t-none p-2 z-10">
                                                        <li>
                                                            <button
                                                                onclick="withdraw_modal.show(); actionId.value = {{$trainingAssignment->id}}"
                                                            >
                                                                Withdraw Request
                                                            </button>
                                                        </li>
                                                    </ul>
                                                </details>
                                            </li>
                                        </ul>
                                    @else
                                        <p>None</p>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endunless
            </div>
        @endif
    </div>
@endsection
