<?php

namespace App\Http\Controllers\Events;

use App\Http\Controllers\Controller;
use App\Models\Event;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::where('start', '>=', now())->orderBy('start', 'asc')->take(3)->get();

        return view('events.index', ['events' => $events]);
    }

    public function show(string $id)
    {
        $event = Event::findOrFail($id);

        return view('events.show', ['event' => $event]);
    }
}
