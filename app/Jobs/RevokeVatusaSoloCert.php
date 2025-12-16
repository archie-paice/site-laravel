<?php

namespace App\Jobs;

use App\Models\SoloCert;
use Http;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Http\Client\ConnectionException;

class RevokeVatusaSoloCert implements ShouldQueue
{
    use Queueable;

    private readonly string $API_URL;
    /**
     * Create a new job instance.
     */
    public function __construct(private readonly SoloCert $soloCert)
    {
        $this->API_URL = config('app.vatusa_api_url') . '/v2/solo';
    }

    /**
     * Execute the job.
     * @throws ConnectionException
     */
    public function handle(): void
    {
        $request = Http::delete($this->API_URL, [
            'apikey' => config('app.vatusa_api_key'),
            'cid' => $this->soloCert->user_id,
            'position' => $this->soloCert->position,
        ]);
    }
}
