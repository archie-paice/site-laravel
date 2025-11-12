<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;

class AuditLogController extends Controller
{
    public function index() {
        $logs = Activity::paginate(100);

        return view('admin.audit-log.index', compact('logs'));
    }
}
