<?php

namespace App\Jobs;

use App;
use App\DTOs\VatusaFacilityInfoDTO;
use App\DTOs\VatusaRosterUser;
use App\Models\Staff;
use App\Models\User;
use DateTime;
use Illuminate\Contracts\Broadcasting\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;

use function Pest\Laravel\json;

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
        $this->ROSTER_API_ENDPOINT = config('app.vatusa_api_url') . '/facility/' . config('app.vatusa_facility') . '/roster/both';
        $this->FACILITY_INFO_ENDPOINT = config('app.vatusa_api_url') . '/facility/' . config('app.vatusa_facility');
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
        }

        if (App::environment('local', 'development')) {
            $testUsers = User::where([
                'first_name' => "Web"
            ])->get();

            foreach ($testUsers as $user) {
                $user->assignRole('admin', 'staff', 'training', 'events', 'facilities', 'instructor', 'core');
                $user->rostered = true;
                $user->save();
            }
        };
    }

    private function updateStaffMembers() {
        Staff::truncate();

        $facilityInfo = Http::get($this->FACILITY_INFO_ENDPOINT, [
            'apikey' => config('app.vatusa_api_key')
        ]);

        $infoDTO = new VatusaFacilityInfoDTO($facilityInfo['data']);

        Staff::fromFacilityInfoDTO($infoDTO);
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
