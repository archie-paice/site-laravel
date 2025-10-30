<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;

use function Pest\Laravel\json;

class SyncRoster implements ShouldQueue
{
    use Queueable;
    const ROSTER_API_ENDPOINT = env('VATUSA_API_URL').'//facility/'.env('VATUSA_FACILITY').'/roster/both';

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $rosterData = Http::get(self::ROSTER_API_ENDPOINT);
    }
}
