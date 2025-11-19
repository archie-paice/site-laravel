<?php

namespace App\Jobs;

use App\Models\TrainingTicket;
use DateTime;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncTrainingTickets implements ShouldQueue
{
    use Queueable;
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
        //https://api.vatusa.net/v2/training/record/{recordID}
        $unsyncedTickets = TrainingTicket::where(['vatusa_synced' => false]);

        foreach ($unsyncedTickets->get() as $ticket) {
            $this->createVatusaTrainingTicket($ticket);
        }
    }


    private function createVatusaTrainingTicket(mixed $ticket)
    {
        $API_URL = config('app.vatusa_api_url').'/v2/user/'.$ticket->user_id.'/training/record';

        try {
            $request = Http::post($API_URL, [
                'apikey' => config('app.vatusa_api_key'),
                'instructor_id' => $ticket->instructor_id,
                'session_date' => (new DateTime($ticket->session_start))->format('Y-m-d H:i'),
                'duration' => $ticket->duration,
                'position' => $ticket->position,
                'movements' => $ticket->movements,
                'score' => $ticket->score,
                'notes' => $ticket->notes,
                'location' => $ticket->location,
            ]);

            if ($request->ok()) {
                $ticket->vatusa_synced = true;
                $ticket->save();
            } else {
                echo('Request Failed: '.$request);
            }
        } catch (ConnectionException $e) {
            echo $e;
            Log::error($e->getMessage());
        }
    }
}
