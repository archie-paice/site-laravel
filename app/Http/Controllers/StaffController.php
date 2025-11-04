<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index() {
        $staff = Staff::all()[0]->user();

        return view('staff.index', ['staff' => $staff]);
    }
}
