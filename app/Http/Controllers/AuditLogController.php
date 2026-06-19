<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Activity;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AuditLogController extends Controller
{
    public function index(Request $request) {
        $cid = $request->query('cid');
        $type = $request->query('type');

        // Roster for the controller picker (typeahead on the page).
        $controllers = User::orderBy('last_name')
            ->orderBy('first_name')
            ->get(['id', 'first_name', 'last_name', 'rating']);

        $selectedController = $cid ? User::find($cid) : null;

        // Distinct record (subject) types present in the log, for the type filter.
        $recordTypes = Activity::query()
            ->whereNotNull('subject_type')
            ->distinct()
            ->pluck('subject_type')
            ->mapWithKeys(fn ($type) => [$type => Str::headline(class_basename($type))])
            ->sort();

        // Note: subject is NOT eager-loaded. Its morph type may reference a model
        // class that no longer exists on this branch (e.g. App\Models\Faq), and
        // eager-loading would try to instantiate every type up front and fail.
        $logs = $this->filteredQuery($cid, $type)
            ->with('causer')
            ->paginate(25)
            ->withQueryString();

        return view('audit-log.index', compact('logs', 'controllers', 'selectedController', 'cid', 'type', 'recordTypes'));
    }

    public function export(Request $request): StreamedResponse {
        $cid = $request->query('cid');
        $type = $request->query('type');
        $limit = (int) $request->query('limit');

        $filename = 'audit-log-' . now()->utc()->format('Ymd-His') . 'Z.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        return response()->streamDownload(function () use ($cid, $type, $limit) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Time (UTC/Zulu)',
                'Action',
                'Who',
                'Causer ID',
                'Record Type',
                'Record ID',
                'Record Name',
                'What Changed',
            ]);

            $writeRow = function ($log) use ($handle) {
                fputcsv($handle, [
                    $log->created_at->utc()->format('Y-m-d H:i:s') . 'Z',
                    $log->event ?? $log->description,
                    $log->causer?->name ?? 'System',
                    $log->causer?->id,
                    $log->subject_type ? class_basename($log->subject_type) : '',
                    $log->subject_id,
                    $this->subjectName($log),
                    $this->describeChanges($log),
                ]);
            };

            $query = $this->filteredQuery($cid, $type)->with('causer');

            if ($limit > 0) {
                // Limit overrides chunking — take the most recent N rows.
                $query->limit($limit)->get()->each($writeRow);
            } else {
                $query->chunk(500, fn ($logs) => $logs->each($writeRow));
            }

            fclose($handle);
        }, $filename, $headers);
    }

    /**
     * Shared base query so the on-screen log and the export stay in sync.
     * When a CID is selected, returns entries where that user is either the
     * causer (made the change) or the subject (was changed).
     */
    private function filteredQuery($cid, $type = null) {
        return Activity::query()
            ->when($cid, function ($query, $cid) {
                $query->where(function ($query) use ($cid) {
                    $query->where(fn ($query) => $query->where('causer_type', User::class)->where('causer_id', $cid))
                        ->orWhere(fn ($query) => $query->where('subject_type', User::class)->where('subject_id', $cid));
                });
            })
            ->when($type, fn ($query, $type) => $query->where('subject_type', $type))
            ->orderBy('created_at', 'desc');
    }

    /**
     * Resolve the subject's display name, tolerating model classes that no longer exist.
     */
    private function subjectName(Activity $log): ?string {
        $subject = rescue(fn () => $log->subject, null, false);

        if (! $subject) {
            return null;
        }

        return $subject->name ?? ('#' . $subject->getKey());
    }

    /**
     * Flatten an activity's property diff into a single readable string for export.
     */
    private function describeChanges(Activity $log): string {
        $new = collect($log->properties['attributes'] ?? []);
        $old = collect($log->properties['old'] ?? []);
        $event = $log->event ?? $log->description;

        $parts = $new->keys()->merge($old->keys())->unique()->map(function ($key) use ($new, $old, $event) {
            $from = $this->stringifyValue($old->get($key));
            $to = $this->stringifyValue($new->get($key));

            if ($event === 'updated') {
                if ($from === $to) {
                    return null;
                }

                return Str::headline($key) . ": {$from} -> {$to}";
            }

            return Str::headline($key) . ': ' . ($to !== '' ? $to : $from);
        })->filter();

        return $parts->implode('; ');
    }

    private function stringifyValue($value): string {
        if (is_null($value) || $value === '') {
            return '';
        }
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }
        if (is_array($value)) {
            return json_encode($value);
        }

        return (string) $value;
    }
}
