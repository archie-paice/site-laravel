<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $homeControllers = User::where('rostered', true)->where('facility', 'ZJX')->count();
        $visitingControllers = User::where('rostered', true)->whereNot('facility', 'ZJX')->count();

        $atm = Staff::where(['title_short' => 'ATM'])->first();
        $datm = Staff::where(['title_short' => 'DATM', 'primary_contact' => true])->first();

        return view('admin.index', [
            'homeControllers' => $homeControllers,
            'visitingControllers' => $visitingControllers,
            'atm' => $atm,
            'datm' => $datm,
        ]);
    }
}
