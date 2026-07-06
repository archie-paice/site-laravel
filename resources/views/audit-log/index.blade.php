@extends('layouts.admin')

@section('title', 'Audit Log')

@php
    use Illuminate\Support\Str;

    $display = function (mixed $value): string {
        if (is_null($value) || $value === '') {
            return '∅';
        }
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }
        if (is_array($value) || is_object($value)) {
            return json_encode($value) ?: '∅';
        }
        if (is_scalar($value)) {
            return (string) $value;
        }
        return '∅';
    };
@endphp

@section('body')
    @php
        $ctrlLabel = fn ($c) => $c->first_name . ' ' . $c->last_name . ' (' . $c->rating->mapToString() . ') — ' . $c->id;
        $ctrlListJs = $controllers->map(fn ($c) => [
            'id' => $c->id,
            'label' => $ctrlLabel($c),
        ]);
        $ctrlInitLbl = $selectedController ? $ctrlLabel($selectedController) : '';
        $ctrlInitId = $cid ?? '';
    @endphp

    <style>[x-cloak]{display:none !important;}</style>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('controllerPicker', () => ({
                open: false,
                query: @json($ctrlInitLbl),
                selectedId: @json($ctrlInitId),
                controllers: @json($ctrlListJs),
                get filtered() {
                    const sel = this.controllers.find(c => c.id == this.selectedId);
                    if (!this.query || (sel && sel.label === this.query)) return this.controllers;
                    const q = this.query.toLowerCase();
                    return this.controllers.filter(c => c.label.toLowerCase().includes(q) || String(c.id).includes(q));
                },
                choose(c) {
                    this.selectedId = c.id;
                    this.query = c.label;
                    this.open = false;
                },
                clearSelection() {
                    this.selectedId = '';
                    this.open = true;
                },
                onEnter(e) {
                    const first = this.filtered[0];
                    if (first) {
                        this.choose(first);
                        this.$nextTick(() => e.target.form.submit());
                    }
                },
                handleSubmit(e) {
                    if (this.query && !this.selectedId) {
                        const first = this.filtered[0];
                        if (first) { this.selectedId = first.id; this.query = first.label; }
                    }
                    if (!this.query) this.selectedId = '';
                    this.$nextTick(() => e.target.submit());
                },
            }));
        });
    </script>

    <p class="text-sm opacity-70 mb-4">
        Search a controller by name to pull their log (by CID). Times are shown in UTC (Zulu).
    </p>

    <div class="flex flex-wrap items-end justify-between gap-4 mb-4">
        <form method="GET" action="{{ route('logs.index') }}" x-data="controllerPicker"
              @submit.prevent="handleSubmit($event)" class="flex flex-wrap items-end gap-2">
            <div class="flex flex-col gap-1">
                <label class="text-sm">Record type</label>
                <select name="type" class="select select-sm">
                    <option value="">All types</option>
                    @foreach ($recordTypes as $class => $label)
                        <option value="{{ $class }}" @selected($type === $class)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex flex-col gap-1 w-full sm:w-64 relative" @click.outside="open = false">
                <label class="text-sm">Controller</label>
                <input type="hidden" name="cid" :value="selectedId">
                <div class="relative">
                    <input type="text" x-model="query"
                        @focus="open = true"
                        @input="clearSelection()"
                        @keydown.escape="open = false"
                        @keydown.enter.prevent="onEnter($event)"
                        @keydown.arrow-down.prevent="open = true"
                        placeholder="All Controllers"
                        class="input input-sm w-full pr-8">
                    <span class="absolute right-2 top-1/2 -translate-y-1/2 pointer-events-none text-base-content/40">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </span>
                    <div x-show="open" x-cloak
                        class="absolute z-50 top-full left-0 mt-1 w-full bg-base-200 border border-base-300 rounded-lg shadow-lg">
                        <ul class="max-h-52 overflow-y-auto">
                            <li>
                                <button type="button" @click="choose({ id: '', label: '' })"
                                    class="w-full text-left px-3 py-2 text-sm hover:bg-base-300 transition-colors"
                                    :class="selectedId === '' ? 'font-semibold' : ''">All Controllers</button>
                            </li>
                            <template x-for="c in filtered" :key="c.id">
                                <li>
                                    <button type="button" @click="choose(c)"
                                        class="w-full text-left px-3 py-2 text-sm hover:bg-base-300 transition-colors"
                                        :class="selectedId == c.id ? 'font-semibold' : ''"
                                        x-text="c.label"></button>
                                </li>
                            </template>
                            <li x-show="filtered.length === 0" class="px-3 py-2 text-sm text-base-content/50">No results</li>
                        </ul>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-sm">Search</button>
        </form>

        {{-- Export exactly what's shown — carries the current view filters (controller + record type) plus an optional line cap. --}}
        <form method="GET" action="{{ route('logs.export') }}" class="flex flex-wrap items-end gap-2">
            <input type="hidden" name="cid" value="{{ $cid }}">
            <input type="hidden" name="type" value="{{ $type }}">

            <div class="flex flex-col gap-1">
                <label class="text-sm">Lines</label>
                <input type="number" name="limit" min="1" placeholder="All" class="input input-sm w-24">
            </div>

            <button type="submit" class="btn btn-outline btn-sm">
                Export CSV{{ ($cid || $type) ? ' (filtered)' : '' }}
            </button>
        </form>
    </div>

    <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
        <div class="flex items-center gap-2">
            @if ($selectedController)
                <span class="badge badge-primary badge-lg">
                    Showing log for {{ $selectedController->name }} (CID {{ $selectedController->id }})
                </span>
                <a href="{{ route('logs.index') }}" class="btn btn-ghost btn-xs">Clear</a>
            @endif
        </div>

        <span class="text-sm opacity-70">
            Showing {{ $logs->count() }} of {{ $logs->total() }} {{ Str::plural('entry', $logs->total()) }}
        </span>
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
