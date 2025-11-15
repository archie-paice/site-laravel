<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\TrainingTicket;
use App\Models\User;
use Illuminate\Http\Request;

class TrainingTicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $trainingTickets = TrainingTicket::all();
        return view('training-tickets.index', compact('trainingTickets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::where(['rostered' => true])->get();
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
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
