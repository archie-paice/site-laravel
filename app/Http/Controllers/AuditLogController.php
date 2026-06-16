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
        $search = $request->input('search');

        // Note: subject is NOT eager-loaded. Its morph type may reference a model
        // class that no longer exists on this branch (e.g. App\Models\Faq), and
        // eager-loading would try to instantiate every type up front and fail.
        $logs = $this->filteredQuery($search)
            ->with('causer')
            ->paginate(25)
            ->withQueryString();

        return view('audit-log.index', compact('logs', 'search'));
    }

    public function export(Request $request): StreamedResponse {
        $search = $request->input('search');

        $filename = 'audit-log-' . now()->utc()->format('Ymd-His') . 'Z.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        return response()->streamDownload(function () use ($search) {
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

            $this->filteredQuery($search)
                ->with('causer')
                ->chunk(500, function ($logs) use ($handle) {
                    foreach ($logs as $log) {
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
                    }
                });

            fclose($handle);
        }, $filename, $headers);
    }

    /**
     * Shared, search-aware base query so the on-screen log and the export stay in sync.
     */
    private function filteredQuery(?string $search) {
        return Activity::query()
            ->when(filled($search), function ($query) use ($search) {
                $terms = preg_split('/\s+/', trim($search));

                $query->where(function ($query) use ($search, $terms) {
                    $query->where('description', 'like', "%{$search}%")
                        ->orWhere('event', 'like', "%{$search}%")
                        ->orWhere('subject_type', 'like', "%{$search}%")
                        // Match the person who made the change (causer)...
                        ->orWhereHasMorph('causer', [User::class], fn ($query) => $this->matchUser($query, $terms))
                        // ...and the person whose record was changed (subject).
                        ->orWhereHasMorph('subject', [User::class], fn ($query) => $this->matchUser($query, $terms));
                });
            })
            ->orderBy('created_at', 'desc');
    }

    /**
     * Match a user across name, operating initials, email and CID. Each whitespace-separated
     * term must match somewhere (AND between terms), so "web 9" matches a user whose details
     * contain both "web" and "9" — e.g. initials "WEB" with CID 9.
     */
    private function matchUser($query, array $terms) {
        foreach ($terms as $term) {
            $query->where(function ($query) use ($term) {
                $query->where('first_name', 'like', "%{$term}%")
                    ->orWhere('last_name', 'like', "%{$term}%")
                    ->orWhere('operating_initials', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%")
                    ->orWhere('id', 'like', "%{$term}%")
                    ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$term}%"]);
            });
        }
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
