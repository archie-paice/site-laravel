

@extends('layouts.admin')

@section('title', 'Create Training Ticket')

@section('body')
    <div class="card card-body bg-base-300 max-w-150">
        <form
            class="flex flex-col"
            action="{{route('solo-certs.store')}}"
            method="POST"
        >
            @csrf

            <label for="userId" class="label">Student</label>
            <select
                name="userId"
                id=""
                class="select"
                value="{{old('userId')}}"
                required
            >
                <option value="">Select a Student</option>

                @foreach($users as $user)
                    <option value="{{$user->id}}">{{$user->nameReversed}} ({{$user->id}})</option>
                @endforeach
            </select>

            <br>

            <label for="issuedBy" class="label">Issued By</label>
            <input type="text" class="input" readonly value="{{auth()->user()->name}}">

            <br>

            <label for="position" class="label">Position (must be in XXX_XXX form)</label>
            @error('position')
            <p>Position must be in XXX_XXX format</p>
            @enderror
            <input
                type="text"
                name="position"
                class="input"
                value="{{old('position')}}"
                oninput="this.value = this.value.toUpperCase();"
                pattern="^([A-Z]{2,3})(_([A-Z]{1,3}))?_(DEL|GND|TWR|APP|DEP|CTR)$"
                required
            >

            <br>

            <div class="flex flex-row max-w-full">
                <input type="checkbox" class="checkbox mr-2" name="confirm" required>
                <label for="confirm" class="">I understand that this will be synced to VATUSA and update the user's relavant training assignments to "Solo Cert" status.</label>
            </div>

            <div class="card-actions mt-5">
                <button
                    class="btn btn-primary"
                    type="submit"
                >Submit</button>
            </div>
        </form>
    </div>
@endsection


