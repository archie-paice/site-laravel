<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $trainingAssignments = Auth::user()->trainingAssignmentsAsStudent;
        $trainingTickets = Auth::user()->trainingTicketsAsStudent;

        return view('profile.index',
            [
                'user' => $user,
                'trainingAssignments' => $trainingAssignments,
                'trainingTickets' => $trainingTickets,
            ]);
    }
}
