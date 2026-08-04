<?php

use App\Jobs\SyncRoster;
use Illuminate\Bus\UniqueLock;
use Illuminate\Contracts\Broadcasting\ShouldBeUnique as BroadcastingShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldBeUnique as QueueShouldBeUnique;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Illuminate\Support\Facades\Http;

test('the roster sync job is queue-unique so it cannot run twice concurrently', function () {
    $job = new SyncRoster;

    // Must implement the *queue* contract; the broadcasting one is a no-op for
    // queued jobs and silently disables the uniqueness that prevents duplicate
    // syncs at the top of the hour.
    expect($job)->toBeInstanceOf(QueueShouldBeUnique::class);
    expect($job)->not->toBeInstanceOf(BroadcastingShouldBeUnique::class);
});

test('a second roster sync dispatch is blocked while one is already locked', function () {
    $lock = new UniqueLock(app('cache')->store());

    expect($lock->acquire(new SyncRoster))->toBeTrue();        // first run acquires the lock
    expect($lock->acquire(new SyncRoster))->toBeFalse();       // duplicate is blocked

    $lock->release(new SyncRoster);

    expect($lock->acquire(new SyncRoster))->toBeTrue();        // lock frees after release
    $lock->release(new SyncRoster);
});

test('given a roster sync, when it completes, then the rostered role matches roster membership', function () {
    $this->seed(PermissionSeeder::class);

    $now = (new DateTime)->format('Y-m-d H:i:s');

    $joiningUser = User::factory()->create(['id' => 100, 'rostered' => false]);
    $leavingUser = User::factory()->create(['id' => 200, 'rostered' => true]);
    $leavingUser->assignRole('rostered');

    Http::fake([
        '*/roster/both*' => Http::response([
            'data' => [
                [
                    'cid' => 100,
                    'fname' => 'Test',
                    'lname' => 'Test',
                    'rating' => 6,
                    'email' => 'test@test.com',
                    'facility' => 'ZJX',
                    'created_at' => $now,
                    'updated_at' => $now,
                    'flag_needbasic' => false,
                    'flag_xferOverride' => false,
                    'facility_join' => $now,
                    'flag_homecontroller' => true,
                    'lastactivity' => $now,
                    'flag_broadcastOptedIn' => false,
                    'flag_preventStaffAssign' => false,
                    'discord_id' => null,
                    'flag_nameprivacy' => false,
                    'last_competency_date' => $now,
                    'promotion_eligible' => false,
                    'transfer_eligible' => false,
                    'roles' => [],
                    'isMentor' => false,
                    'isSupIns' => false,
                    'last_promotion' => $now,
                ],
            ],
        ]),
        '*/v2/facility/*' => Http::response([
            'data' => [
                'facility' => [
                    'info' => [
                        'atm' => 100,
                        'datm' => 100,
                        'ta' => 100,
                        'wm' => 100,
                        'ec' => 100,
                        'fe' => 100,
                    ],
                    'roles' => [
                        ['cid' => 100, 'role' => 'ATM', 'created_at' => $now],
                    ],
                ],
            ],
        ]),
    ]);

    (new SyncRoster)->handle();

    expect($joiningUser->fresh()->rostered)->toBeTrue();
    expect($joiningUser->fresh()->hasRole('rostered'))->toBeTrue();
    expect($leavingUser->fresh()->rostered)->toBeFalse();
    expect($leavingUser->fresh()->hasRole('rostered'))->toBeFalse();
});
