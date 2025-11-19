<?php

use App\DTOs\VatusaRosterUser;
use App\Jobs\SyncRoster;
use App\Models\User;

test('given a vatusa user, when converted to a database user, then a database user is input and accurate', function () {
    $now = new DateTime();
    $now = $now->format('Y-m-d H:i:s');

    $vatusa = new VatusaRosterUser([
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
        'flag_homecontroller' => false,
        'lastactivity' => $now,
        'flag_broadcastOptedIn' => false,
        'flag_preventStaffAssign' => false,
        'discord_id' => null,
        'flag_nameprivacy' => false,
        'last_competency_date' => $now,
        'promotion_eligible'=> false,
        'transfer_eligible' => false,
        'roles' => [],
        'isMentor' => false,
        'isSupIns' => false,
        'last_promotion' => $now
    ]);

    User::updateFromVatusa($vatusa);
    $user = User::find($vatusa->cid);

    expect($user->id)->toBe($vatusa->cid);
    expect($user->first_name)->toBe($vatusa->firstName);
    expect($user->last_name)->toBe($vatusa->lastName);
    expect($user->email)->toBe($vatusa->email);
    expect($user->facility)->toBe($vatusa->facility);
    expect($user->joined_at)->toBe($now);
});

test('given the roster sync function exists, when the roster sync function is executed, then it executes without errors', function() {
    SyncRoster::dispatch();

});
