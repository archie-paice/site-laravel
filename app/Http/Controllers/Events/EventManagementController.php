<?php

namespace App\Http\Controllers\Events;

use App\Enums\EventType;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventPosition;
use App\Models\EventPositionPreset;
use App\Models\FeaturedField;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class EventManagementController extends Controller
{
    /**
     * Banner uploads are capped below php.ini's upload_max_filesize so that Laravel
     * reports the size problem itself instead of PHP silently rejecting the upload
     * and leaving the generic "The image failed to upload." message.
     *
     * SVG is deliberately excluded: banners are served straight from public storage,
     * and an SVG can carry script.
     */
    private const IMAGE_MAX_KILOBYTES = 8192;

    private const IMAGE_RULES = 'nullable|file|image|mimes:jpeg,png,jpg,gif,webp|max:'.self::IMAGE_MAX_KILOBYTES;

    private const IMAGE_MESSAGES = [
        'image.max' => 'The banner image must be 8 MB or smaller. Please compress or resize it and try again.',
        'image.mimes' => 'The banner image must be a JPEG, PNG, GIF, or WebP file.',
        'image.image' => 'The banner must be an image file.',
        'image.uploaded' => 'The banner image could not be uploaded. It is most likely larger than the 8 MB limit.',
    ];

    public function index()
    {
        $events = Event::all();

        return view('events.admin', ['events' => $events]);
    }

    public function toggleVisibility(Event $event)
    {
        if ($event->archived) {
            return back()->with('error', 'Archived events must remain hidden.');
        }

        $event->update([
            'hidden' => ! $event->hidden,
        ]);

        return back();
    }

    public function toggleArchived(Event $event)
    {
        if ($event->archived) {
            $event->update([
                'archived' => false,
                'archived_at' => null,
                'hidden' => true,
            ]);
        } else {
            $event->update([
                'archived' => true,
                'archived_at' => now(),
                'hidden' => true,
            ]);
        }

        return back();
    }

    public function togglePositionsLocked(Event $event)
    {
        $event->update([
            'positions_locked' => ! $event->positions_locked,
        ]);

        return back();
    }

    public function manage(string $id)
    {
        $event = Event::findorFail($id);
        $registrants = EventPosition::where('event_id', $event->id)->get();
        $mostRequestedPosition = $registrants
            ->groupBy('requested_position')
            ->map(fn ($group) => $group->count())
            ->sortDesc()
            ->keys()
            ->first();

        return view('events.manage', compact('event', 'registrants', 'mostRequestedPosition'));
    }

    public function create()
    {
        $event = new Event;
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
                }, ],
            'type' => [new Enum(EventType::class)],
            'featured_fields' => 'nullable|string',
            'presetPositions' => ['nullable', 'string', Rule::exists(EventPositionPreset::class, 'name')],
            'image' => self::IMAGE_RULES,
        ], self::IMAGE_MESSAGES);

        $presetName = $validated['presetPositions'] ?? null;
        $presetPositions = EventPositionPreset::where('name', $presetName)->first();
        $presetPositions = $presetPositions?->positions;

        $featuredFields = $this->parseFeaturedFields($validated['featured_fields'] ?? null);

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
            $event->event_image_route = $this->storeBanner($request, $event);
            $event->save();
        }

        return redirect()->route('admin.events.index')->with('success', 'Event created successfully!');
    }

    public function edit($id)
    {
        $event = Event::findOrFail($id);
        $types = EventType::cases();
        $featuredFields = FeaturedField::orderBy('name')->pluck('name');
        $presetPositions = EventPositionPreset::orderBy('name')->pluck('name');

        return view('events.edit', [
            'event' => $event,
            'types' => $types,
            'featuredFields' => $featuredFields,
            'presetPositions' => $presetPositions,
        ]);
    }

    public function update(Request $request, $id)
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
                }, ],
            'type' => [new Enum(EventType::class)],
            'featured_fields' => 'nullable|string',
            'presetPositions' => ['nullable', 'string', Rule::exists(EventPositionPreset::class, 'name')],
            'image' => self::IMAGE_RULES,
        ], self::IMAGE_MESSAGES);

        $event = Event::findOrFail($id);
        $oldImagePath = $event->event_image_route;

        $event->title = $validated['title'];
        $event->description = $validated['description'];
        $event->start = $validated['start'];
        $event->end = $validated['end'];
        $event->type = $validated['type'];
        $event->featured_fields = $this->parseFeaturedFields($validated['featured_fields'] ?? null);

        // A blank preset selection leaves the event's existing positions alone; picking one
        // replaces them, so presets can still be applied after the event has been created.
        if (filled($validated['presetPositions'] ?? null)) {
            $newPositions = EventPositionPreset::where('name', $validated['presetPositions'])
                ->firstOrFail()->positions ?? [];

            // Same guard as EventPositionsManagement::save() — a preset must not pull a
            // position out from under a registrant who is already assigned to it.
            $assignedRemoved = EventPosition::where('event_id', $event->id)
                ->whereNotNull('assigned_position')
                ->whereNotIn('assigned_position', $newPositions)
                ->pluck('assigned_position')
                ->unique()
                ->values();

            if ($assignedRemoved->isNotEmpty()) {
                return back()->withInput()->with(
                    'error',
                    'That preset does not include assigned position(s): '.$assignedRemoved->implode(', ')
                );
            }

            $event->presetPositions = $newPositions;
        }

        if ($request->hasFile('image')) {
            $event->event_image_route = $this->storeBanner($request, $event);

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
        $event = Event::findOrFail($id);

        if ($event->event_image_route) {
            Storage::disk('public')->delete(str_replace('storage/', '', $event->event_image_route));
        }

        $event->delete();

        return redirect()->route('admin.events.index')->with('success', 'Event deleted successfully');
    }

    /**
     * @return list<string>
     */
    private function parseFeaturedFields(?string $featuredFields): array
    {
        if (blank($featuredFields)) {
            return [];
        }

        return array_values(array_filter(array_map('trim', explode(',', $featuredFields)), 'strlen'));
    }

    /**
     * Derive the stored extension from the file's contents rather than the client-supplied
     * name, so an attacker cannot choose the extension the banner is served under.
     */
    private function storeBanner(Request $request, Event $event): string
    {
        $image = $request->file('image');
        $imageName = 'event_'.$event->id.'.'.$image->extension();

        return 'storage/'.$image->storeAs('event', $imageName, 'public');
    }
}
