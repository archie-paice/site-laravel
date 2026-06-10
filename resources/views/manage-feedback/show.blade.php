@extends('layouts.admin')

@section('title', 'Feedback #' . $feedback->id)

@section('body')
    <div class="max-w-2xl">
        {{-- Manage actions --}}
        <div class="flex flex-row gap-2 mb-5">
            <a href="{{ route('admin.feedback.index') }}" class="btn btn-ghost">&larr; Back to Feedback</a>
            {{-- TODO: manage buttons (approve / forward to controller / delete) go here --}}
        </div>

        <x-card-component title="Feedback from {{ $feedback->user->name }}">
            <p class="text-sm opacity-70 mb-4">
                Submitted
                <time data-local datetime="{{ $feedback->created_at->toIso8601String() }}">
                    {{ $feedback->created_at->timezone('America/New_York')->format('m-d-Y g:i A') }} ET
                </time>
            </p>

            <div class="flex flex-col gap-y-4">
                <div class="grid md:grid-cols-3 grid-cols-1 gap-4">
                    <label class="flex flex-col">
                        <span class="label-text font-semibold mb-1">Name</span>
                        <input type="text" class="input input-bordered w-full bg-base-300 text-base-content/60" value="{{ $feedback->user->name }}" disabled>
                    </label>

                    <label class="flex flex-col">
                        <span class="label-text font-semibold mb-1">CID</span>
                        <input type="text" class="input input-bordered w-full bg-base-300 text-base-content/60" value="{{ $feedback->user_id }}" disabled>
                    </label>

                    <label class="flex flex-col">
                        <span class="label-text font-semibold mb-1">Email</span>
                        <input type="email" class="input input-bordered w-full bg-base-300 text-base-content/60" value="{{ $feedback->user->email }}" disabled>
                    </label>
                </div>

                <div class="grid md:grid-cols-2 grid-cols-1 gap-4">
                    <label class="flex flex-col">
                        <span class="label-text font-semibold mb-1">Controller</span>
                        <input type="text" class="input input-bordered w-full bg-base-300 text-base-content/60"
                               value="{{ $feedback->controller->name }} - {{ $feedback->controller_id }}" disabled>
                    </label>

                    <label class="flex flex-col">
                        <span class="label-text font-semibold mb-1">Position</span>
                        <input type="text" class="input input-bordered w-full bg-base-300 text-base-content/60" value="{{ $feedback->position }}" disabled>
                    </label>
                </div>

                <label class="flex flex-col">
                    <span class="label-text font-semibold mb-1">Experience</span>
                    <input type="text" class="input input-bordered w-full bg-base-300 text-base-content/60" value="{{ $feedback->experience }}" disabled>
                </label>

                <label class="flex flex-col">
                    <span class="label-text font-semibold mb-1">Comments / Feedback</span>
                    <textarea rows="8" class="textarea textarea-bordered bg-base-300 text-base-content/60 w-full resize-none" disabled>{{ $feedback->comments }}</textarea>
                </label>

                <div class="flex items-center gap-x-3 w-max">
                    @if ($feedback->staff_followup)
                        <span class='text-success font-bold text-lg'>&check;</span>
                    @else
                        <span class='text-error font-bold text-lg'>&cross;</span>
                    @endif
                    <span>Staff follow-up requested</span>
                </div>
            </div>
        </x-card-component>
    </div>
@endsection
