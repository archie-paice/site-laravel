<?php

namespace App\Jobs;

use App;
use App\DTOs\VatusaFacilityInfoDTO;
use App\DTOs\VatusaRosterUser;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Contracts\Broadcasting\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Http;
use Illuminate\Support\Facades\Log;

class SyncRoster implements ShouldQueue, ShouldBeUnique
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
    }

    private function updateRoster() {
        $ROSTER_API_ENDPOINT = config('app.vatusa_api_url') . '/v2/facility/' . config('app.vatusa_facility') . '/roster/both';

        $rosterData = Http::get($ROSTER_API_ENDPOINT, [
            'apikey' => config('app.vatusa_api_key')
        ]);

        if ($rosterData->failed()) {
            throw new \Exception('Failed to fetch roster data: ' . $rosterData->status() . ' - ' . $rosterData->body());
        }

        $roster = $rosterData->json();
        User::where(['rostered' => true])->update(['rostered' => false]);

        for ($i = 0; $i < count($roster['data']); $i++) {
            $vatusaUser = new VatusaRosterUser($roster['data'][$i]);

            User::updateFromVatusa($vatusaUser);
        }

        // Clear hanging OIs
        User::where([
            'rostered' => false
        ])->update([
            'operating_initials' => null
        ]);
    }

    private function clearUserRoles() {
        $users = User::all();

        foreach ($users as $user) {
            $user->removeRole('staff', 'admin', 'training', 'events', 'facilities', 'instructor');
        }
    }

    private function assignRoles() {
        $staffMembers = Staff::all();

        foreach ($staffMembers as $staff) {
            $user = $staff->user;

            switch ($staff->title_short) {
                case 'ATM':
                case 'DATM':
                    $user?->assignRole('admin', 'training', 'instructor', 'facilities', 'events', 'staff');
                    break;
                case 'TA':
                    $user?->assignRole('admin', 'training', 'staff');
                    break;
                case 'WM':
                    $user?->assignRole('admin', 'training', 'instructor', 'facilities', 'events', 'staff');
                    break;
                case 'EC':
                    $user?->assignRole('events', 'staff');
                    break;
                case 'FE':
                    $user?->assignRole('facilities', 'staff');
                    break;
                case 'INS':
                    $user?->assignRole('instructor', 'training', 'staff');
                    break;
                case 'MTR':
                    $user?->assignRole('training', 'staff');
                    break;
            }
        }
    }

    private function updateStaffMembers() {
        $FACILITY_INFO_ENDPOINT = config('app.vatusa_api_url') . '/v2/facility/' . config('app.vatusa_facility');
        $facilityInfo = Http::get($FACILITY_INFO_ENDPOINT, [
            'apikey' => config('app.vatusa_api_key')
        ]);

        if ($facilityInfo->failed()) {
            throw new \Exception('Failed to fetch facility info: ' . $facilityInfo->status() . ' - ' . $facilityInfo->body());
        }

        $this->clearUserRoles();
        Staff::truncate();

        $infoDTO = new VatusaFacilityInfoDTO($facilityInfo->json()['data']);

        Staff::fromFacilityInfoDTO($infoDTO);

        $this->assignRoles();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $this->updateRoster();

            $this->updateStaffMembers();

            if (App::environment() == 'development') {
                $testUsers = User::where([
                    'first_name' => "Web"
                ])->get();

                foreach ($testUsers as $user) {
                    $user->assignRole('admin', 'staff', 'training', 'events', 'facilities', 'instructor');
                    $user->rostered = true;
                    $user->division = 'USA';
                    $user->facility = 'ZJX';
                    $user->save();
                }
            }

            Log::info('Roster sync completed successfully.');
        } catch (\Exception $e) {
            // Log error
            Log::error('Error syncing roster: '.$e->getMessage().'\n'.$e->getTraceAsString(), [
                'url' => config('app.vatusa_api_url') . '/v2/facility/' . config('app.vatusa_facility'),
                'environment' => App::environment(),
                'exception' => get_class($e)
            ]);
        }
    }
}
