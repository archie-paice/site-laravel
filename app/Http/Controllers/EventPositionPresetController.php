<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventPositionPreset;

class EventPositionPresetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $positions = EventPositionPreset::all();
        return view('admin.events.position-preset.index', compact('positions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $preset = new EventPositionPreset();

        return view('admin.events.position-preset.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'positions' => 'required|string',
        ]);

        $positions = explode(',', $validated['positions']);
        $positions = array_map('trim', $positions);

        EventPositionPreset::create([
            'name' => $validated['name'],
            'positions' => $positions,
        ]);

        return redirect()->route('position-preset.index')
            ->with('success', 'Preset created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $position = EventPositionPreset::find($id);
        return view('admin.events.position-preset.edit', ['position' => $position]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $position = EventPositionPreset::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string',
            'positions' => 'required|string',
        ]);

        $positions = array_filter(array_map('trim', explode(',', $validated['positions'])));

        $position->update([
            'name' => $validated['name'],
            'positions' => $positions,
        ]);

        return redirect()->route('position-preset.index')
            ->with('success', 'Preset updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $position = EventPositionPreset::find($id);
        $position->delete();
        return redirect()->route('position-preset.index')->with('success', 'Preset deleted successfully');
    }
}
