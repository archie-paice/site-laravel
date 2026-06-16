@extends('layouts.admin')

@section('title', 'Audit Log')

@php
    use Illuminate\Support\Str;

    $display = function ($value) {
        if (is_null($value) || $value === '') {
            return '∅';
        }
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }
        if (is_array($value)) {
            return json_encode($value);
        }
        return (string) $value;
    };
@endphp

@section('body')
    <div class="flex flex-wrap items-end justify-between gap-4 mb-4">
        <div>
            <h1 class="text-2xl font-bold">Audit Log</h1>
            <p class="text-sm opacity-70">A record of who changed what, and when. Times are shown in UTC (Zulu).</p>
        </div>
        <div class="flex items-end gap-2">
            <x-search/>
            <a href="{{ route('logs.export', request()->only('search')) }}"
               class="btn btn-outline btn-sm">
                Export CSV{{ $search ? ' (filtered)' : '' }}
            </a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="table table-zebra align-top">
            <thead>
            <tr>
                <th>Action</th>
                <th>Who</th>
                <th>Record</th>
                <th>What changed</th>
                <th>When</th>
            </tr>
            </thead>
            <tbody>
            @forelse($logs as $log)
                @php
                    $event = $log->event ?? $log->description;
                    $badge = match ($event) {
                        'created' => 'badge-success',
                        'updated' => 'badge-info',
                        'deleted' => 'badge-error',
                        default => 'badge-ghost',
                    };

                    $new = collect($log->properties['attributes'] ?? []);
                    $old = collect($log->properties['old'] ?? []);
                    $keys = $new->keys()->merge($old->keys())->unique();
                @endphp
                <tr>
                    <td>
                        <span class="badge {{ $badge }} badge-sm capitalize">{{ $event }}</span>
                    </td>
                    <td>
                        @if ($log->causer)
                            <div class="font-medium">{{ $log->causer->name ?? 'Unknown' }}</div>
                            <div class="text-xs opacity-60">CID {{ $log->causer->id }}</div>
                        @else
                            <span class="italic opacity-60">System</span>
                        @endif
                    </td>
                    <td>
                        @if ($log->subject_type)
                            {{-- The subject's model class may no longer exist on this branch (or its file
                                 may have been removed from a stale autoloader); rescue() any failure. --}}
                            @php $subject = rescue(fn () => $log->subject, null, false); @endphp
                            <div class="font-medium">{{ Str::headline(class_basename($log->subject_type)) }}</div>
                            @if ($subject)
                                <div class="text-xs opacity-60">{{ $subject->name ?? '#' . $subject->getKey() }}</div>
                            @elseif ($log->subject_id)
                                <div class="text-xs opacity-60">#{{ $log->subject_id }} (deleted)</div>
                            @endif
                        @else
                            <span class="opacity-50">—</span>
                        @endif
                    </td>
                    <td>
                        @if ($keys->isEmpty())
                            <span class="opacity-50">—</span>
                        @else
                            <div class="flex flex-col gap-1">
                                @foreach ($keys as $key)
                                    @php
                                        $from = $old->get($key);
                                        $to = $new->get($key);
                                    @endphp
                                    @continue($event === 'updated' && $from === $to)
                                    <div class="text-xs">
                                        <span class="font-semibold">{{ Str::headline($key) }}:</span>
                                        @if ($event === 'updated')
                                            <span class="opacity-60 line-through">{{ $display($from) }}</span>
                                            <span aria-hidden="true">→</span>
                                            <span class="font-medium">{{ $display($to) }}</span>
                                        @else
                                            <span>{{ $display($to ?? $from) }}</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </td>
                    <td>
                        <div class="font-mono whitespace-nowrap" title="{{ $log->created_at->diffForHumans() }}">{{ $log->created_at->utc()->format('Y-m-d H:i:s') }}Z</div>
                        <div class="text-xs opacity-60">{{ $log->created_at->diffForHumans() }}</div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="py-8 text-center opacity-60">No audit entries found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $logs->links() }}
    </div>
@endsection
