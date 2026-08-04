@extends('layouts.admin')

@section('title', 'Feedback #' . $feedback->id)

@section('body')
    <div class="max-w-2xl">
        {{-- Manage actions --}}
        <div class="flex flex-row flex-wrap items-center gap-2 mb-5">
            <a href="{{ route('admin.feedback.index') }}" class="btn btn-ghost">&larr; Back to Feedback</a>

            <div class="grow"></div>

            <span class="badge badge-lg
                @if ($feedback->status === \App\Enums\FeedbackStatus::RELEASED) badge-success
                @elseif ($feedback->status === \App\Enums\FeedbackStatus::STASHED) badge-error
                @else badge-neutral @endif">
                {{ $feedback->status->label() }}
            </span>
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
                    <input type="text" class="input input-bordered w-full bg-base-300 text-base-content/60" value="{{ $feedback->experience->value }}" disabled>
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

                <div class="flex flex-row flex-wrap justify-end gap-2">
                    @if ($feedback->status === \App\Enums\FeedbackStatus::STASHED)
                        <form action="{{ route('admin.feedback.unstash', [$feedback]) }}" method="POST">
                            @method('PUT')
                            @csrf
                            <button type="submit" class="btn btn-warning">Unstash</button>
                        </form>
                    @elseif ($feedback->status === \App\Enums\FeedbackStatus::PENDING)
                        <form action="{{ route('admin.feedback.stash', [$feedback]) }}" method="POST">
                            @method('PUT')
                            @csrf
                            <button type="submit" class="btn btn-error">Stash</button>
                        </form>
                    @endif

                    @unless ($feedback->status === \App\Enums\FeedbackStatus::RELEASED)
                        <form action="{{ route('admin.feedback.release', [$feedback]) }}" method="POST">
                            @method('PUT')
                            @csrf
                            <button type="submit" class="btn btn-success">Release</button>
                        </form>
                    @endunless
                </div>
            </div>
        </x-card-component>
    </div>
@endsection
