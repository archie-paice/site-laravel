@extends('layouts.admin')

@section('body')
    <div class="card bg-base-300">
        <div class="card-body">
v
        <div class="flex flex-col gap-x-5 p-5">
            <div class="card card-body bg-base-200 w-max">
                <h1 class="card-title">Membership</h1>
                <p><strong>Home:</strong> {{ $homeControllers }}</p>
                <p><strong>Visiting:</strong> {{ $visitingControllers }}</p>
                <p><strong>Total:</strong> {{ $homeControllers + $visitingControllers }}</p>
            </div>
        </div>
    </div>
@endsection