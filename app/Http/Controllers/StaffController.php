<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index() {
        $atm = Staff::where(['title_short' => 'ATM'])->first();
        $datm = Staff::where(['title_short' => 'DATM', 'primary_contact' => true])->first();
        $ta = Staff::where(['title_short' => 'TA', 'primary_contact' => true])->first();
        $ec = Staff::where(['title_short' => 'EC', 'primary_contact' => true])->first();
        $fe = Staff::where(['title_short' => 'FE', 'primary_contact' => true])->first();
        $wm = Staff::where(['title_short' => 'WM', 'primary_contact' => true])->first();
        $webTeam = Staff::where(['title_short' => 'WM', 'primary_contact' => false])->get();
        $eventsTeam = Staff::where(['title_short' => 'EC', 'primary_contact' => false])->get();
        $facilitiesTeam = Staff::where(['title_short' => 'FE', 'primary_contact' => false])->get();
        $trainingTeam = Staff::where(['title_short' => 'ATA'])->get();
        $mentors = Staff::where(['title_short' => 'MTR'])->get();
        $instructors = Staff::where(['title_short' => 'INS'])->get();

        // fix: Attempt to read property "user" on null
        return view('staff.index', [
            'staff' => Staff::all(),
            'atm' => $atm,
            'datm' => $datm,
            'ta' => $ta,
            'ec' => $ec,
            'fe' => $fe,
            'wm' => $wm,
            'webTeam' => $webTeam,
            'eventsTeam' => $eventsTeam,
            'facilitiesTeam' => $facilitiesTeam,
            'trainingTeam' => $trainingTeam,
            'mentors' => $mentors,
            'instructors' => $instructors
        ]);
    }
}
