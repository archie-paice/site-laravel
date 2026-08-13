@extends('layouts.admin')

@section('title', 'Staffing Requests')

@section('body')
    <x-search/>

    @unless ($staffingRequests->isEmpty())
        <table class='table table-zebra table-md w-max border-2 border-base-300 mt-5'>
            <thead>
                <tr>
                    <th>Submitted</th>
                    <th>Submitted By</th>
                    <th>Event Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($staffingRequests as $staffingRequest)
                    <tr>
                        <td class='whitespace-nowrap'>
                            <time data-local datetime="{{ $staffingRequest->created_at->toIso8601String() }}">
                                {{ $staffingRequest->created_at->timezone('America/New_York')->format('m-d-Y g:i A') }} ET
                            </time>
                        </td>
                        <td>
                            <a href="{{ route('users.show', [$staffingRequest->user_id]) }}">
                                {{ $staffingRequest->user->name }} - {{ $staffingRequest->user_id }}
                            </a>
                        </td>
                        <td>{{ $staffingRequest->name }}</td>
                        <td>
                            <ul class='text-accent menu menu-horizontal h-10 items-center gap-x-5 justify-center'>
                                <li>
                                    <details>
                                        <summary>Actions</summary>
                                        <ul class="bg-base-100 text-base-content rounded-t-none p-2 z-10">
                                            <li><a href="{{ route('admin.staffing-requests.show', [$staffingRequest]) }}">View</a></li>

                                            @haspermission('staffing-requests:write')
                                                <li>
                                                    <form action="{{ route('admin.staffing-requests.destroy', [$staffingRequest]) }}" method="POST">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button type="submit">Close</button>
                                                    </form>
                                                </li>
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
            {{ $staffingRequests->appends(request()->query())->links() }}
        </div>
    @else
        <h1>No staffing requests found.</h1>
    @endunless
@endsection
