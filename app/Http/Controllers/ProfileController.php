<?php

namespace App\Http\Controllers;

use App\Models\TrainingAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index() {
        $user = Auth::user();
        $trainingAssignments = Auth::user()->trainingAssignmentsAsStudent;

        return view('profile.index', ['user' => $user, 'trainingAssignments' => $trainingAssignments]);
    }
}
