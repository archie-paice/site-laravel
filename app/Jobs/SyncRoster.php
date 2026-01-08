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

class SyncRoster implements ShouldQueue, ShouldBeUnique
{
    use Queueable;
    private readonly string $ROSTER_API_ENDPOINT;
    private readonly string $FACILITY_INFO_ENDPOINT;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $this->ROSTER_API_ENDPOINT = config('app.vatusa_api_url') . '/v2/facility/' . config('app.vatusa_facility') . '/roster/both';
        $this->FACILITY_INFO_ENDPOINT = config('app.vatusa_api_url') . '/v2/facility/' . config('app.vatusa_facility');
    }

    private function updateRoster() {
        $rosterData = Http::get($this->ROSTER_API_ENDPOINT, [
            'apikey' => config('app.vatusa_api_key')
        ]);

        $roster = json_decode($rosterData, true);
        User::where(['rostered' => true])->update(['rostered' => false]);

        for ($i = 0; $i < count($roster['data']); $i++) {
            $vatusaUser = new VatusaRosterUser($roster['data'][$i]);

            User::updateFromVatusa($vatusaUser);
            echo "Updated user: " . $vatusaUser->cid . "\n";
        }

        if (App::environment('local', 'development')) {
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
        };
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
                    $user?->assignRole('staff', 'admin');
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
        $facilityInfo = Http::get($this->FACILITY_INFO_ENDPOINT, [
            'apikey' => config('app.vatusa_api_key')
        ]);

        $this->clearUserRoles();
        Staff::truncate();

        $infoDTO = new VatusaFacilityInfoDTO($facilityInfo['data']);

        Staff::fromFacilityInfoDTO($infoDTO);

        $this->assignRoles();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->updateRoster();

        $this->updateStaffMembers();
    }
}
