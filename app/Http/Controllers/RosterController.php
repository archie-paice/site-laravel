<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CertificationFacility;

class RosterController extends Controller {
    public function index() {
        $users = User::where('rostered', true)->orderBy('last_name')->get();
        $certificaionFacilities = CertificationFacility::all();
        return view('roster.index', 
        ['users' => $users,
         'certificationFacilities' => $certificaionFacilities
        ]);
    }
}