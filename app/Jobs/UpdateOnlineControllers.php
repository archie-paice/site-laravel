<?php

namespace App\Jobs;

use App\DTOs\OnlineControllerDTO;
use App\Models\OnlineController;
use App\Models\StatisticsPrefixes;
use Http;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Str;

class UpdateOnlineControllers implements ShouldQueue
{
    use Queueable;

    private string $API_ENDPOINT;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $this->API_ENDPOINT = env('VATSIM_API_URL') . '/v2/atc/online';
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $onlineData = Http::get($this->API_ENDPOINT);

        $controllers = json_decode($onlineData, true);
        $prefixes = StatisticsPrefixes::pluck('name')->toArray();
        OnlineController::truncate();

        foreach ($controllers as $controller) {
            $onlineController = new OnlineControllerDTO($controller);
            if (Str::startsWith($onlineController->callsign, $prefixes)) {
                echo 'ran';
                OnlineController::fromDTO($onlineController);
            }
        }  
    }
}
