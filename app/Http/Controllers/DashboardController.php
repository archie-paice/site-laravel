<?php

namespace App\Http\Controllers;

use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $homeControllers = User::where('rostered', true)->where('facility', 'ZJX')->count();
        $visitingControllers = User::where('rostered', true)->whereNot('facility', 'ZJX')->count();

        return view('admin.index', [
            'homeControllers' => $homeControllers,
            'visitingControllers' => $visitingControllers,
        ]);
    }
}
