<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'identifier' => 'required|string|max:10|unique:certification_facilities,identifier',
        ]);

        CertificationFacility::create($validated);

        return redirect()->route('certification-facilities.index')->with('success', 'Certification Facility created successfully.');
    }

    public function destroy(int $id) 
    {
        CertificationFacility::destroy($id);

        return redirect()->route('certification-facilities.index')->with('success', 'Certification Facility deleted successfully.');
    }
}
