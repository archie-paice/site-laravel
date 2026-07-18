<?php

namespace App\Jobs;

use App\Mail\ControllerRemovedFromRoster;
use App\Models\User;
use Http;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class RemoveUserFromRoster implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $userId, public string $reason)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user = User::find($this->userId);

        if (! $user) {
            Log::warning('RemoveUserFromRoster: user '.$this->userId.' not found, skipping.');

            return;
        }

        $facility = config('app.vatusa_facility');
        $isVisitor = strcasecmp((string) $user->facility, (string) $facility) !== 0;

        // Visitors are managed through the manageVisitor endpoint; home controllers
        // are removed from the primary facility roster.
        $URL = $isVisitor
            ? config('app.vatusa_api_url').'/v2/facility/'.$facility.'/roster/manageVisitor/'.$this->userId
            : config('app.vatusa_api_url').'/v2/facility/'.$facility.'/roster/'.$this->userId;

        $request = Http::delete($URL, [
            'apikey' => config('app.vatusa_api_key'),
            'reason' => $this->reason,
        ]);

        if ($request->failed()) {
            Log::error('Failed to remove user '.$this->userId.' from roster. Response: '.$request->body());

            return;
        }

        // Reflect the removal locally so the change is visible before the next
        // full roster sync runs.
        $user->rostered = false;
        $user->operating_initials = null;
        $user->save();

        Mail::to($user->email)->bcc(['atm@zjxartcc.org', 'datm@zjxartcc.org'])->queue(new ControllerRemovedFromRoster($user, $this->reason));

        Log::info('Successfully removed user '.$this->userId.' from roster. Reason: '.$this->reason);
    }
}
