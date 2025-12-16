<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class AuditLogController extends Controller
{
    public function index(Request $request) {
        $logs = Activity::orderBy('created_at', 'desc')->paginate(25);

        return view('audit-log.index', compact('logs'));
    }
}
