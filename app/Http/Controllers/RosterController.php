<?php

namespace App\Http\Controllers;

use App\Models\CertificationFacility;
use App\Models\User;

class RosterController extends Controller
{
    public function index()
    {
        $users = User::where('rostered', true)->orderBy('last_name')->get();
        $certificaionFacilities = CertificationFacility::all();

        return view('roster.index',
            ['users' => $users,
                'certificationFacilities' => $certificaionFacilities,
            ]);
    }
}
