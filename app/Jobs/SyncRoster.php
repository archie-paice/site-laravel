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
        $this->ROSTER_API_ENDPOINT = env('VATUSA_API_URL') . '/facility/' . env('VATUSA_FACILITY') . '/roster/both';
        $this->FACILITY_INFO_ENDPOINT = env('VATUSA_API_URL') . '/facility/' . env('VATUSA_FACILITY');
    }

    private function updateRoster() {
        $rosterData = Http::get($this->ROSTER_API_ENDPOINT, [
            'apikey' => env('VATUSA_API_KEY')
        ]);

        $roster = json_decode($rosterData, true);
        User::where(['rostered' => true])->update(['rostered' => false]);

        for ($i = 0; $i < count($roster['data']); $i++) {
            $vatusaUser = new VatusaRosterUser($roster['data'][$i]);

            User::updateFromVatusa($vatusaUser);
        }

        if (App::environment('local', 'development')) {
            $user = User::createOrFirst([
                'id' => 10000010,
            ], [
                'id' => 10000010,
                'first_name' => 'Web',
                'last_name' => 'Ten',
                'email' => 'web10@vatusa.net',
                'rating' => 11,
                'joined_at' => new DateTime(),
                'division' => 'USA',
                'facility' => 'ZJX',
                'rostered' => true,]);

            $user->assignRole('admin', 'staff', 'training', 'events', 'facilities', 'instructor', 'core');

        };
    }

    private function updateStaffMembers() {
        $facilityInfo = Http::get($this->FACILITY_INFO_ENDPOINT, [
            'apikey' => env('VATUSA_API_KEY')
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
