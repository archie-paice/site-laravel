@extends('layouts.admin')

@section('body')
    <div class="flex flex-row gap-x-10">
        <div class="card bg-base-300 w-max">
            <div class="card-body">
                <h1 class="card-title">Membership</h1>
                <p><strong>Home:</strong> {{ $homeControllers }}</p>
                <p><strong>Visiting:</strong> {{ $visitingControllers }}</p>
                <p><strong>Total:</strong> {{ $homeControllers + $visitingControllers }}</p>
            </div>
        </div>
    </div>
@endsection