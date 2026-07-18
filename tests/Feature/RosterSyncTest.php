<?php

use App\Jobs\SyncRoster;
use Illuminate\Bus\UniqueLock;
use Illuminate\Contracts\Broadcasting\ShouldBeUnique as BroadcastingShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldBeUnique as QueueShouldBeUnique;

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
