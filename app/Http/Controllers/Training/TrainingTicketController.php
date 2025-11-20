<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\TrainingTicket;
use App\Models\User;
use Auth;
use DateTime;
use Exception;
use Illuminate\Http\Request;

class TrainingTicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $request->input('search');
        $trainingTickets = TrainingTicket::search($query)->paginate(25);

        return view('training-tickets.index', compact('trainingTickets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::where(['rostered' => true])->orderBy('last_name')->get();
        return view('training-tickets.create', [
            'users' => $users
        ]);
        ///^([A-Z]{2,3})(_([A-Z]{1,3}))?_(DEL|GND|TWR|APP|DEP|CTR)$/
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $instructor = Auth::user();

        $validated = $request->validate([
            'student' => 'required|integer',
            'position' => ['required', 'regex:/^([A-Z]{2,3})(_([A-Z]{1,3}))?_(DEL|GND|TWR|APP|DEP|CTR)$/'],
            'location' => 'required|integer|min:0|max:2',
            'sessionStart' => 'required|date',
            'sessionEnd' => 'required|date|after:sessionStart',
            'movements' => 'required|integer',
            'score' => 'required|integer|between:1,5',
            'notes' => 'required|min:20|max:2048',
        ]);

        if($instructor->id == $validated['student']) {
            return redirect()->back()->with('error', 'Cannot create training ticket with yourself as the student.');
        }



        $ticket = new TrainingTicket([
            'user_id' => $validated['student'],
            'instructor_id' => $instructor->id,
            'position' => $validated['position'],
            'session_start' => $validated['sessionStart'],
            'session_end' => $validated['sessionEnd'],
            'movements' => $validated['movements'],
            'score' => $validated['score'],
            'notes' => $validated['notes'],
            'location' => $validated['location'],
        ]);

        $ticket->save();

        return redirect(route('training-tickets.show', [$ticket]))
            ->with('success', 'Training ticket successfully created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $trainingTicket = TrainingTicket::findOrFail($id);
        return view('training-tickets.show', compact('trainingTicket'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $trainingTicket = TrainingTicket::findOrFail($id);
        return view('training-tickets.edit', ['trainingTicket' => $trainingTicket]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'position' => ['required', 'regex:/^([A-Z]{2,3})(_([A-Z]{1,3}))?_(DEL|GND|TWR|APP|DEP|CTR)$/'],
            'location' => 'required|integer|min:0|max:2',
            'sessionStart' => 'required|date',
            'sessionEnd' => 'required|date|after:sessionStart',
            'movements' => 'required|integer',
            'score' => 'required|integer|between:1,5',
            'notes' => 'required|min:20|max:2048',
        ]);

        $ticket = TrainingTicket::findOrFail($id)->update([
            'position' => $validated['position'],
            'session_start' => $validated['sessionStart'],
            'session_end' => $validated['sessionEnd'],
            'movements' => $validated['movements'],
            'score' => $validated['score'],
            'notes' => $validated['notes'],
            'location' => $validated['location'],
            'vatusa_synced' => false
        ]);


        return redirect(route('training-tickets.show', [$id]))
            ->with('success', 'Training ticket successfully updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
