<?php

namespace App\Http\Controllers\Events;

use App\Enums\EventType;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventPositionPreset;
use App\Models\FeaturedField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Enum;

class EventController extends Controller
{
    public function index() {
        // perform some sort of calculation here to determine the next 3 upcoming events and then pass them to the view
        $events = Event::where('start', '>=', now())->orderBy('start', 'asc')->take(3)->get();
        return view('events.index', ['events' => $events]);
    }
    
    public function show(string $id)
    {
        $event = Event::findOrFail($id);

        return view('events.show', ['event' => $event]);
    }
}
