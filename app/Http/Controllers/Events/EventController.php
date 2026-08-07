<?php

namespace App\Http\Controllers\Events;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::where('start', '>=', now())
            ->where('hidden', false)
            ->where('archived', false)
            ->orderBy('start', 'asc')
            ->take(3)
            ->get();

        return view('events.index', ['events' => $events]);
    }

    public function show(string $id)
    {
        $event = Event::findOrFail($id);

        // Hiding an event has to hold for a direct link too, not just the calendar.
        // Staff who can manage events still need to preview it.
        if (($event->hidden || $event->archived) && ! Auth::user()?->hasPermissionTo('manage events')) {
            abort(404);
        }

        return view('events.show', ['event' => $event]);
    }
}
