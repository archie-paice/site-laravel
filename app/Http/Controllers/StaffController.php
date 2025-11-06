<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index() {
        $atm = Staff::where(['title_short' => 'ATM'])->first()->user;
        $datm = Staff::where(['title_short' => 'DATM', 'primary_contact' => true])->first()->user;
        $ta = Staff::where(['title_short' => 'TA', 'primary_contact' => true])->first()->user;
        $ec = Staff::where(['title_short' => 'EC', 'primary_contact' => true])->first()->user;
        $fe = Staff::where(['title_short' => 'FE', 'primary_contact' => true])->first()->user;
        $wm = Staff::where(['title_short' => 'WM', 'primary_contact' => true])->first()->user;

        return view('staff.index', [
            'staff' => Staff::all(),
            'atm' => $atm,
            'datm' => $datm,
            'ta' => $ta,
            'ec' => $ec,
            'fe' => $fe,
            'wm' => $wm
        ]);
    }
}
