<?php

namespace App\Console\Commands;

use App\Jobs\SyncStatsimSessions;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class SyncStatsim extends Command
{
    protected $signature = 'stats:sync {months=12 : Number of past months to sync}';
    protected $description = 'Sync controller statistics from Statsim (runs synchronously)';

    public function handle(): void
    {
        $months = (int) $this->argument('months');
        $cursor = Carbon::now()->subMonthsNoOverflow($months - 1)->startOfMonth();
        $end    = Carbon::now()->startOfMonth();

        $this->info("Syncing {$months} months from {$cursor->format('M Y')} to {$end->format('M Y')}...");
        $bar = $this->output->createProgressBar($months);
        $bar->start();

        while ($cursor->lessThanOrEqualTo($end)) {
            (new SyncStatsimSessions($cursor->year, $cursor->month))->handle();
            $bar->advance();
            $cursor->addMonthNoOverflow();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Sync complete.');
    }
}
