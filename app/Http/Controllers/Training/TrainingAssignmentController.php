<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\TrainingAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrainingAssignmentController extends Controller
{
    private readonly array $VALID_TRAINING_TYPES;

    public function __construct()
    {
        $this->VALID_TRAINING_TYPES = ['S1', 'S2', 'S3', 'C1', 'MCO GND', 'MCO TWR', 'F11 TRACON'];
    }

    public function index() {
        return view('training-assignment.index');
    }

    public function create(Request $request) {
        $validated = $request->validate([
            'trainingType' => 'string|required'
        ], [
            'trainingType.required' => 'Training Type must be specified.'
        ]);

        if (!in_array($validated['trainingType'], $this->VALID_TRAINING_TYPES)) {
            return redirect()->back(400)->with('error', 'Invalid Training Type');
        } else {
            TrainingAssignment::create([
                'training_type' => $validated['trainingType'],
                'trainee_id' => Auth::user()->id,
                'instructor_id' => null
            ]);

            return redirect()->back()->with('success', 'Training requested successfully');
        }
    }
}
