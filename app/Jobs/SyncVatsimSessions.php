<?php

namespace App\Jobs;

use App\Models\ControllerMonthlyStat;
use App\Models\ControllerSession;
use App\Models\StatisticsPrefixes;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SyncVatsimSessions implements ShouldQueue
{
    use Queueable;

    public int $timeout = 300;

    public int $tries = 3;

    public array $backoff = [60, 300];

    public function __construct(public string $from, public string $to) {}

    public function handle(): void
    {
        $from = Carbon::parse($this->from)->utc();
        $to = Carbon::parse($this->to)->utc();
        $limit = max(1, config('app.vatsim_statistics_page_size'));
        $offset = 0;
        $pages = 0;
        $accepted = 0;
        $skipped = 0;
        $touchedMonths = [];
        $prefixes = StatisticsPrefixes::pluck('name')->all();
        $rosteredCids = User::where('rostered', true)->pluck('id')->flip();

        do {
            try {
                $response = Http::timeout(60)
                    ->retry(3, fn (int $attempt) => $attempt * 1000)
                    ->get(rtrim(config('app.vatsim_api_url'), '/').'/v2/atc/history', [
                        'start_date' => $from->toIso8601String(),
                        'end_date' => $to->toIso8601String(),
                        'limit' => $limit,
                        'offset' => $offset,
                    ]);
            } catch (\Throwable $exception) {
                Log::error('VATSIM statistics sync request failed', [
                    'exception' => $exception::class,
                    'message' => $exception->getMessage(),
                    'from' => $from->toIso8601String(),
                    'to' => $to->toIso8601String(),
                    'pages' => $pages,
                ]);

                return;
            }

            if (! $response->successful()) {
                Log::error('VATSIM statistics sync failed', [
                    'status' => $response->status(),
                    'body' => Str::limit($response->body(), 500),
                    'from' => $from->toIso8601String(),
                    'to' => $to->toIso8601String(),
                    'pages' => $pages,
                ]);

                return;
            }

            $payload = $response->json();
            $sessions = $payload['items'] ?? null;

            if (! is_array($sessions)) {
                Log::error('VATSIM statistics sync returned an unexpected payload', [
                    'from' => $from->toIso8601String(),
                    'to' => $to->toIso8601String(),
                    'pages' => $pages,
                ]);

                return;
            }

            $pages++;

            foreach ($sessions as $session) {
                $connection = $session['connection_id'] ?? null;
                $connectionId = $connection['id'] ?? null;
                $callsign = $connection['callsign'] ?? null;
                $vatsimId = $connection['vatsim_id'] ?? null;
                $loggedOn = $connection['start'] ?? null;
                $loggedOff = $connection['end'] ?? null;

                if (! $connectionId || ! $callsign || ! $vatsimId || ! $loggedOn || ! $loggedOff) {
                    $skipped++;

                    continue;
                }

                $start = Carbon::parse($loggedOn)->utc();
                $end = Carbon::parse($loggedOff)->utc();
                $userId = (int) $vatsimId;

                if ($start->lessThan($from) || $start->greaterThan($to)
                    || ! Str::startsWith($callsign, $prefixes)
                    || ! $rosteredCids->has($userId)) {
                    $skipped++;

                    continue;
                }

                $facilityLevel = $this->facilityLevel(Str::upper(Str::substr($callsign, -3)));
                if ($facilityLevel < 2) {
                    $skipped++;

                    continue;
                }

                $existing = ControllerSession::find($connectionId);

                if ($existing) {
                    $this->touchMonth($touchedMonths, $existing->user_id, $existing->start);
                }

                ControllerSession::updateOrCreate(
                    ['id' => $connectionId],
                    [
                        'callsign' => $callsign,
                        'user_id' => $userId,
                        'facility_level' => $facilityLevel,
                        'start' => $start,
                        'end' => $end,
                    ]
                );

                $this->touchMonth($touchedMonths, $userId, $start);
                $accepted++;
            }

            $offset += count($sessions);
        } while (count($sessions) === $limit);

        foreach ($touchedMonths as $userId => $months) {
            foreach ($months as [$year, $month]) {
                $this->recomputeMonthlyStats($userId, $year, $month);
            }
        }

        Log::info('VATSIM statistics sync complete', [
            'from' => $from->toIso8601String(),
            'to' => $to->toIso8601String(),
            'pages' => $pages,
            'accepted' => $accepted,
            'skipped' => $skipped,
        ]);
    }

    private function facilityLevel(string $suffix): int
    {
        return match ($suffix) {
            'DEL' => 2,
            'GND' => 3,
            'TWR' => 4,
            'APP', 'DEP' => 5,
            'CTR', 'FSS' => 6,
            default => 0,
        };
    }

    private function touchMonth(array &$touchedMonths, int $userId, Carbon $start): void
    {
        $touchedMonths[$userId][$start->format('Y-m')] = [$start->year, $start->month];
    }

    private function recomputeMonthlyStats(int $userId, int $year, int $month): void
    {
        $from = Carbon::create($year, $month, 1)->startOfMonth();
        $to = $from->copy()->endOfMonth();

        $sessions = ControllerSession::where('user_id', $userId)
            ->whereBetween('start', [$from, $to])
            ->get();

        $hours = [2 => 0.0, 3 => 0.0, 4 => 0.0, 5 => 0.0, 6 => 0.0];

        foreach ($sessions as $session) {
            $duration = $session->end->diffInSeconds($session->start, true) / 3600;
            if (isset($hours[$session->facility_level])) {
                $hours[$session->facility_level] += $duration;
            }
        }

        ControllerMonthlyStat::updateOrCreate(
            ['user_id' => $userId, 'year' => $year, 'month' => $month],
            [
                'delivery_hours' => $hours[2],
                'ground_hours' => $hours[3],
                'tower_hours' => $hours[4],
                'approach_hours' => $hours[5],
                'center_hours' => $hours[6],
            ]
        );
    }
}
