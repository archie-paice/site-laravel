<?php

namespace App\Http\Controllers\Events;

use App\Enums\EventType;
use App\Livewire\EventRegistrants;
use App\Models\Event;
use App\Models\EventPosition;
use App\Models\EventPositionPreset;
use App\Models\FeaturedField;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Enum;

class EventManagementController
{
    public function index() {
        $events = Event::all();
        return view('events.admin', ['events' => $events]);
    }

    public function toggleVisibility(Event $event) {
        $event->update([
            'hidden' => !request()->has('visible'),
        ]);

        return back();
    }

    public function togglePositionsLocked(Event $event) {
        $event->update([
            'positions_locked' => request()->has('positions_locked'),
        ]);

        return back();
    }

    public function manage(string $id) {
        $event = Event::findorFail($id);
        $registrants = EventPosition::where('event_id', $event->id)->get();
        $mostRequestedPosition = $registrants
            ->groupBy('requested_position')
            ->map(fn($group) => $group->count())
            ->sortDesc()
            ->keys()
            ->first();

        return view ('events.manage', compact('event', 'registrants', 'mostRequestedPosition'));
    }

    public function create()
    {
        $event = new Event();
        $types = EventType::cases();
        $featuredFields = FeaturedField::orderBy('name')->pluck('name');
        $presetPositions = EventPositionPreset::orderBy('name')->pluck('name');

        return view('events.create', [
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
            'start' => 'required|date|before:end',
            'end' => ['required', 'date', 'after:start',
                function ($attribute, $value, $fail) use ($request) {
                    $start = Carbon::parse($request->start);
                    $end = Carbon::parse($value);

                    if ($start->diffInMinutes($end) < 60) {
                        $fail('The end time must be at least 1 hour after the start time.');
                    }
                },],
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

    public function edit($id)
    {
        $event = Event::find($id);
        $types = EventType::cases();
        $featuredFields = FeaturedField::orderBy('name')->pluck('name');

        return view('events.edit', ['event' => $event, 'types' => $types, 'featuredFields' => $featuredFields]);
    }

    public function update(Request $request, $id)
    {
        $featuredFields = FeaturedField::pluck('name')->toArray();

        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'start' => 'required|date|before:end',
            'end' => ['required', 'date', 'after:start',
                function ($attribute, $value, $fail) use ($request) {
                    $start = Carbon::parse($request->start);
                    $end = Carbon::parse($value);

                    if ($start->diffInMinutes($end) < 60) {
                        $fail('The end time must be at least 1 hour after the start time.');
                    }
                },],
            'type' => [new Enum(EventType::class)],
            'featured_fields' => 'required|string',
            'presetPositions' => 'nullable|string',
            'image' => 'file|image|mimes:jpeg,png,jpg,gif,svg|max:2048|required',
        ]);

        $featuredFields = explode(', ', $validated['featured_fields']);
        $featuredFields = array_map('trim', $featuredFields);


        $event = Event::find($id);
        $oldImagePath = $event->event_image_route;

        $event->title = $validated['name'];
        $event->description = $validated['description'];
        $event->start = $validated['start'];
        $event->end = $validated['end'];
        $event->type = $validated['type'];
        $event->featured_fields = $featuredFields ?? [];

        if ($request->hasFile('image')) {
            $imageName = 'event_'.$event->id.'.'.$request->file('image')->getClientOriginalExtension();
            $path = $request->file('image')->storeAs('event', $imageName, 'public');

            $event->event_image_route = 'storage/'.$path;

            // For the sake of storage, delete the old image
            if ($oldImagePath && $oldImagePath !== $event->event_image_route) {
                $cleanPath = str_replace('storage/', '', $oldImagePath);
                Storage::disk('public')->delete($cleanPath);
            }
        }

        $event->save();

        return redirect()->route('admin.events.index')
            ->with('success', 'Event updated successfully.');
    }

    public function destroy($id)
    {
        $event = Event::find($id);
        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Event deleted successfully');
    }
}
