<?php

use App\Jobs\SyncTrainingTickets;
use App\Models\TrainingTicket;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Spatie\Permission\Models\Role;

test('given an unsynced training ticket, when the sync job is called, then the ticket is synced to vatusa', function () {
    Http::fake([
        config('app.vatusa_api_url').'/v2/user/1697197/training/record' => Http::response(['data' => ['id' => '12345']], 200),
    ]);

    Role::firstOrCreate(['name' => 'core', 'guard_name' => 'web']);

    // Ensure the referenced user exists for the FK constraints in tests
    if (!User::find(1697197)) {
        User::factory()->create(['id' => 1697197, 'first_name' => 'Test', 'last_name' => 'User', 'email' => 'test+1697197@example.test']);
    }

    $ticket = new TrainingTicket([
        'user_id' => 1697197,
        'instructor_id' => 1697197,
        'position' => 'MCO_APP',
        'session_start' => now()->format('Y-m-d H:i:s'),
        'session_end' => now()->addHour()->format('Y-m-d H:i:s'),
        'movements' => 10,
        'score' => 5,
        'notes' => "these are some notes about something",
        'location' => 1,
    ]);

    $ticket->save();

    (new SyncTrainingTickets())->handle();

    $ticket = TrainingTicket::findOrFail($ticket->id);
    expect($ticket->vatusa_synced)->toBeTrue();
    expect($ticket->vatusa_id)->toBeAlphaNumeric();
});
