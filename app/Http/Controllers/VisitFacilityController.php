<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\VisitingChecklistService;

class VisitFacilityController extends Controller
{
    public function index()
    {
        return view('visit.index');
    }

    public function create(Request $request, VisitingChecklistService $visitingChecklistService)
    {
        $cid = str(Auth::user()->cid);
        $checklist = $visitingChecklistService->getChecklistItems($cid);

        return view('visit.create', ['checklist' => $checklist]);
    }
}
