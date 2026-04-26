<?php

namespace App\Http\Controllers;

use App\Models\ControllerMonthlyStat;
use App\Models\ControllerSession;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class StatisticsController extends Controller
{
    public function index(Request $request)
    {
        $now   = Carbon::now();
        $year  = (int) $request->query('year', $now->year);
        $month = $request->query('month', $now->month);
        $cid   = $request->query('cid');

        if ($year < 2000 || $year > 2100) $year = $now->year;
        $month = ($month === 'all' || (int) $month === 0) ? 0 : (int) $month;
        if ($month !== 0 && ($month < 1 || $month > 12)) $month = $now->month;

        $years = ControllerMonthlyStat::select('year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        if (!$years->contains($now->year)) {
            $years = $years->prepend($now->year);
        }

        $controllers = User::where('rostered', true)
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get(['id', 'first_name', 'last_name', 'rating']);

        // Individual controller lookup
        $selectedController = null;
        $controllerMonthly  = collect();
        $controllerSessions = collect();

        if ($cid) {
            $selectedController = User::find($cid);

            if ($selectedController) {
                $controllerMonthly = ControllerMonthlyStat::where('user_id', $cid)
                    ->where('year', $year)
                    ->orderBy('month')
                    ->get();

                if ($month !== 0) {
                    $from = Carbon::create($year, $month, 1)->startOfMonth();
                    $to   = $from->copy()->endOfMonth();
                    $controllerSessions = ControllerSession::where('user_id', $cid)
                        ->whereBetween('start', [$from, $to])
                        ->orderBy('start', 'desc')
                        ->get();
                }
            }
        }

        // Leaderboard (only shown when no controller selected)
        if ($month === 0) {
            $rows = ControllerMonthlyStat::with('user')
                ->where('year', $year)
                ->get()
                ->filter(fn($s) => $s->user !== null)
                ->groupBy('user_id')
                ->map(function ($group) {
                    $first = $group->first();
                    $first->delivery_hours = $group->sum('delivery_hours');
                    $first->ground_hours   = $group->sum('ground_hours');
                    $first->tower_hours    = $group->sum('tower_hours');
                    $first->approach_hours = $group->sum('approach_hours');
                    $first->center_hours   = $group->sum('center_hours');
                    return $first;
                })
                ->sortByDesc(fn($s) => $s->totalHours())
                ->values();
        } else {
            $rows = ControllerMonthlyStat::with('user')
                ->where('year', $year)
                ->where('month', $month)
                ->get()
                ->filter(fn($s) => $s->user !== null)
                ->sortByDesc(fn($s) => $s->totalHours())
                ->values();
        }

        $totals = [
            'delivery' => $rows->sum('delivery_hours'),
            'ground'   => $rows->sum('ground_hours'),
            'tower'    => $rows->sum('tower_hours'),
            'approach' => $rows->sum('approach_hours'),
            'center'   => $rows->sum('center_hours'),
            'total'    => $rows->sum(fn($s) => $s->totalHours()),
        ];

        $allTimeHours = ControllerMonthlyStat::selectRaw(
            'SUM(delivery_hours + ground_hours + tower_hours + approach_hours + center_hours) as total'
        )->value('total') ?? 0;

        $earliest = ControllerMonthlyStat::selectRaw('MIN(year * 100 + month) as ym, MIN(year) as y, MIN(month) as m')->first();
        $allTimeSince = ($earliest && $earliest->y)
            ? Carbon::create($earliest->y, $earliest->m, 1)->format('M Y')
            : null;

        return view('statistics.index', [
            'stats'               => $rows,
            'year'                => $year,
            'month'               => $month,
            'years'               => $years,
            'totals'              => $totals,
            'allTimeHours'        => $allTimeHours,
            'allTimeSince'        => $allTimeSince,
            'controllers'         => $controllers,
            'cid'                 => $cid,
            'selectedController'  => $selectedController,
            'controllerMonthly'   => $controllerMonthly,
            'controllerSessions'  => $controllerSessions,
        ]);
    }
}
