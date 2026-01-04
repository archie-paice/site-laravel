@extends('layouts.main')

@section('title', 'Visit the Virtual Jacksonville ARTCC')

@section('body')
    <x-card-component title='Eligibility Requirements'>
        <p class="mb-4">To submit a visiting request to the Virtual Jacksonville ARTCC (vZJX), you must meet the following eligibility requirements:</p>
        @if ($checklist->error)
            <p class='text-lg'>It appears you are not a member of VATUSA. Please read <a class='link link-primary' href="https://www.vatusa.net/help/kb#q12">this FAQ</a> on how to join as an out-of-division controller.</p>
        @else
            <table class='table w-max'>
                <tr>
                    <td>You are not a member of vZJX</td>
                    <td>
                        <x-true-false-display :value="!auth()->user()->rostered"/>
                    </td>
                </tr>
                <tr>
                    <td>You have a home facility</td>
                    <td>
                        <x-true-false-display :value="$checklist->hasHomeFacility"/>
                    </td>
                </tr>
                <tr>
                    <td>You have completed the <a class='link link-primary' href="https://www.vatusa.net/help/kb#q12">appropriate RCE</a> (out-of-division visitors only)</td>
                    <td>
                        <x-true-false-display :value="$checklist->needsBasic"/>
                    </td>
                </tr>
                <tr>
                    <td>You have an S3 rating or higher</td>
                    <td>
                        <x-true-false-display :value="auth()->user()->rating->value >= \App\Enums\ControllerRating::S3->value"/>
                    </td>
                </tr>
                <tr>
                    <td>It has been at least 90 days since promotion</td>
                    <td>
                        <x-true-false-display :value="$checklist->ninetyDaysSincePromotion"/>
                    </td>
                </tr>
                <tr>
                    <td>You controlled at least 50 hours since your last promotion</td>
                    <td>
                        <x-true-false-display :value="$checklist->fiftyHoursSincePromotion"/>
                    </td>
                </tr>
    
            </table>

            <p>For more detailed information, please check your <a class='link link-primary' href="https://www.vatusa.net/my/profile">VATUSA profile.</a></p>

            @if (!$checklist->visitEligible || auth()->user()->rostered || $checklist->error)
                <p class='text-lg text-error'>You are not eligible to submit a visiting request to the Virtual Jacksonville ARTCC (vZJX).</p>
            @else
                <p class='text-lg text-success'>You are eligible to submit a visiting request to the Virtual Jacksonville ARTCC (vZJX)!</p>
            @endif
        @endif
        
        @if ($checklist->visitEligible && !auth()->user()->rostered && !$checklist->error)
            <form action="{{ route('visit.store') }}" method="post">
                @csrf
                <input type="text" class='input' disabled value="CID: {{ auth()->user()->cid }}">
                <button type="submit" class="btn btn-primary">Submit Visiting Request</button>
            </form>
        @endif
    </x-card-component>
@endsection