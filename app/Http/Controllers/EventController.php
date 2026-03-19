<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Enums\EventType;
use App\Models\FeaturedField;
use App\Models\EventPositionPreset;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rule;

class EventController extends Controller
{
    public function manage()
    {
        $events = Event::all();
        return view('manage-events.index', ['events' => $events]);
    }

    public function index() {
        // perform some sort of calculation here to determine the next 3 upcoming events and then pass them to the view
        $events = Event::where('start', '>=', now())->orderBy('start', 'asc')->take(3)->get();
        return view('events.index', ['events' => $events]);
    }

    public function create()
    {
        $event = new Event();
        $types = EventType::cases();
        $featuredFields = FeaturedField::orderBy('name')->pluck('name');
        $presetPositions = EventPositionPreset::orderBy('name')->pluck('name');

        return view('manage-events.create', [
            'types' => $types,
            'featuredFields' => $featuredFields,
            'presetPositions' => $presetPositions,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'start' => 'required|date',
            'end' => 'required|date',
            'type' => [new Enum(EventType::class)],
            'featured_fields' => 'required|string',
            'presetPositions' => 'nullable|string',
            'image' => 'file|image|mimes:jpeg,png,jpg,gif,svg|max:2048|required',
        ]);

        $presetName = $validated['presetPositions'] ?? null;
        $presetPositions = EventPositionPreset::where('name', $presetName)->first();
        $presetPositions = $presetPositions?->positions;


        $featuredFields = explode(', ', $validated['featured_fields']);
        $featuredFields = array_map('trim', $featuredFields);

        $event = Event::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'start' => $validated['start'],
            'end' => $validated['end'],
            'type' => $validated['type'],
            'featured_fields' => $featuredFields,
            'presetPositions' => $presetPositions,
            'event_image_route' => null,
        ]);

        if ($request->hasFile('image')) {
            $imageName = 'event_'.$event->id.'.'.$request->file('image')->getClientOriginalExtension();
            $path = $request->file('image')->storeAs('event', $imageName, 'public');
            $event->event_image_route = 'storage/'.$path;
            $event->save();
        }


        return redirect()->route('admin.events.index')->with('success', 'Event created successfully!');
    }


    public function show(string $id)
    {
        $event = Event::findOrFail($id);

        return view('events.show', ['event' => $event]);
    }

    public function edit($id)
    {
        $event = Event::find($id);
        $types = EventType::cases();
        $featuredFields = FeaturedField::orderBy('name')->pluck('name');

        return view('manage-events.edit', ['event' => $event, 'types' => $types, 'featuredFields' => $featuredFields]);
    }


    public function update(Request $request, $id)
    {
        $featuredFields = FeaturedField::pluck('name')->toArray();

        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'start' => 'required|date',
            'end' => 'required|date',
            'type' => [new Enum(EventType::class)],
            'featured_fields' => ['array'],
            'featured_fields.*' => ['string', Rule::in($featuredFields)],
            'image' => 'file|image|mimes:jpeg,png,jpg,gif,svg|max:2048|required',
        ]);

        $event = Event::find($id);
        $event->update($validated);

        return redirect()->route('admin.events.index')
            ->with('success', 'Post updated successfully.');
    }

    public function destroy($id)
    {
        $event = Event::find($id);
        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Event deleted successfully');
    }
}
