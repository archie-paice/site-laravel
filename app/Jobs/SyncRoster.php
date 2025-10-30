<?php

namespace App\Jobs;

use App\DTOs\VatusaRosterUser;
use Illuminate\Contracts\Broadcasting\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;

use function Pest\Laravel\json;

class SyncRoster implements ShouldQueue, ShouldBeUnique
{
    use Queueable;
    private string $ROSTER_API_ENDPOINT;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $this->ROSTER_API_ENDPOINT = env('VATUSA_API_URL') . '/facility/' . env('VATUSA_FACILITY') . '/roster/both';
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        echo $this->ROSTER_API_ENDPOINT;
        echo env('VATUSA_API_KEY');
        $rosterData = Http::get($this->ROSTER_API_ENDPOINT, [
            'apikey' => env('VATUSA_API_KEY')
        ]);
        echo $rosterData;
        $roster = json_decode($rosterData, true);
        for ($i = 0; $i < count($roster['data']); $i++) {
            echo print_r($roster['data'][$i]);
            $user = new VatusaRosterUser($roster['data'][$i]);
            echo $user->lastName;
        }  
    }
}
