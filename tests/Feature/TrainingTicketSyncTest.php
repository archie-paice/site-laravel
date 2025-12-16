<?php

use App\Jobs\SyncTrainingTickets;
use App\Models\TrainingTicket;

test('given an unsynced training ticket, when the sync job is called, then the ticket is synced to vatusa', function () {
    \App\Jobs\SyncRoster::dispatch();
    $ticket = new TrainingTicket([
        'user_id' => 1697197,
        'instructor_id' => 1697197,
        'position' => 'MCO_APP',
        'session_start' => new DateTime(),
        'session_end' => (new DateTime())->add(new DateInterval('PT1H')),
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
