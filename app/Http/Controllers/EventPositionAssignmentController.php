<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\EventPosition;

class EventPositionAssignmentController extends Controller
{
    public function store(Request $request, Event $event) {
        $data = $request->validate([
            'position_id' => 'required|string|max:255',
        ]);

    EventPosition::create([
        'event_id' => $event->id,
        'position_name' => $data['position_name'],
    ]);
    }
}
