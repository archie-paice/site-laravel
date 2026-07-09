<?php

namespace App\Http\Controllers;

use App\Models\CertificationFacility;

class CertificationFacilityController extends Controller
{
    public function index()
    {
        $certificationFacilities = CertificationFacility::all();
        return view('certification-facilities.index', [
            'certificationFacilities' => $certificationFacilities
        ]);
    }

    public function show(int $id) {
        $facility = CertificationFacility::findOrFail($id);
        return view('certification-facilities.show', [
            'facility' => $facility
        ]);
    }
}
