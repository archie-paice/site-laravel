<?php

namespace App\Http\Controllers;

use App\Models\VisitorRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\VisitingChecklistService;

class VisitFacilityController extends Controller
{
    public function index()
    {
        return view('visit.index');
    }

    public function show(Request $request, int $visitRequest)
    {
        $request = VisitorRequest::findOrFail($visitRequest);
        return view('visit.show', ['request' => $request]);
    }

    public function manage()
    {
        $visitRequests = VisitorRequest::orderBy('created_at', 'desc')->paginate(25);
        return view('visit.manage', ['visitRequests' => $visitRequests]);
    }

    public function create(Request $request, VisitingChecklistService $visitingChecklistService)
    {
        $cid = str(Auth::user()->cid);
        $checklist = $visitingChecklistService->getChecklistItems($cid);

        return view('visit.create', ['checklist' => $checklist]);
    }

    public function store(Request $request) {
        // TODO
    }
}
