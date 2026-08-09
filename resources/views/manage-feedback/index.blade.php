@extends('layouts.admin')

@section('title', 'Feedback Management')

@section('body')
    <x-search/>

    @unless ($feedback->isEmpty())
        <table class='table table-zebra table-md w-max border-2 border-base-300 mt-5'>
            <thead>
                <tr>
                    <th>Submitted</th>
                    <th>Submitted By</th>
                    <th>Controller</th>
                    <th>Position</th>
                    <th>Status</th>
                    <th>Follow-up</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($feedback as $entry)
                    <tr>
                        <td class='whitespace-nowrap'>
                            <time data-local datetime="{{ $entry->created_at->toIso8601String() }}">
                                {{ $entry->created_at->timezone('America/New_York')->format('m-d-Y g:i A') }} ET
                            </time>
                        </td>
                        <td>
                            <a href="{{ route('users.show', [$entry->user_id]) }}">
                                {{ $entry->user->name }} - {{ $entry->user_id }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('users.show', [$entry->controller_id]) }}">
                                {{ $entry->controller->name }} - {{ $entry->controller_id }}
                            </a>
                        </td>
                        <td>{{ $entry->position }}</td>
                        <td>
                            @if ($entry->status === \App\Enums\FeedbackStatus::STASHED)
                                <span class='text-error'>{{ $entry->status->label() }}</span>
                            @elseif ($entry->status === \App\Enums\FeedbackStatus::RELEASED)
                                <span class='text-success'>{{ $entry->status->label() }}</span>
                            @else
                                {{ $entry->status->label() }}
                            @endif
                        </td>
                        <td class='text-center'>
                            @if ($entry->staff_followup)
                                <span class='text-success font-bold text-lg'>&check;</span>
                            @else
                                <span class='text-error font-bold text-lg'>&cross;</span>
                            @endif
                        </td>
                        <td>
                            <ul class='text-accent menu menu-horizontal h-10 items-center gap-x-5 justify-center'>
                                <li>
                                    <details>
                                        <summary>Actions</summary>
                                        <ul class="bg-base-100 text-base-content rounded-t-none p-2 z-10">
                                            <li><a href="{{ route('admin.feedback.show', [$entry]) }}">View</a></li>

                                            @haspermission('feedback:write')
                                            @if ($entry->status === \App\Enums\FeedbackStatus::STASHED)
                                                <li>
                                                    <form action="{{ route('admin.feedback.unstash', [$entry]) }}" method="POST">
                                                        @method('PUT')
                                                        @csrf
                                                        <button type="submit">Unstash</button>
                                                    </form>
                                                </li>
                                            @elseif ($entry->status === \App\Enums\FeedbackStatus::PENDING)
                                                <li>
                                                    <form action="{{ route('admin.feedback.stash', [$entry]) }}" method="POST">
                                                        @method('PUT')
                                                        @csrf
                                                        <button type="submit">Stash</button>
                                                    </form>
                                                </li>
                                            @endif

                                            @unless ($entry->status === \App\Enums\FeedbackStatus::RELEASED)
                                                <li>
                                                    <form action="{{ route('admin.feedback.release', [$entry]) }}" method="POST">
                                                        @method('PUT')
                                                        @csrf
                                                        <button type="submit">Release</button>
                                                    </form>
                                                </li>
                                            @endunless
                                            @endhaspermission
                                        </ul>
                                    </details>
                                </li>
                            </ul>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class='mt-5'>
            {{ $feedback->appends(request()->query())->links() }}
        </div>
    @else
        <h1>No feedback found.</h1>
    @endunless
@endsection
