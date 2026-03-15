<?php

namespace App\Http\Controllers;

use App\Models\CertificationLevel;
use Illuminate\Http\Request;

class CertificationLevelController extends Controller
{
    public function store(Request $request, int $facilityId) {
        $validated = $request->validate([
            'level' => 'required|integer|unique:certification_levels,level,NULL,id,facility_id,' . $facilityId,
            'name' => 'required|string',
            'abbreviation' => 'required|string|max:3',
        ]);

        $certificationLevel = new CertificationLevel();
        $certificationLevel->facility_id = $facilityId;
        $certificationLevel->name = $validated['name'];
        $certificationLevel->abbreviation = $validated['abbreviation'] ?? null;
        $certificationLevel->level = $validated['level'];
        $certificationLevel->save();

        return redirect()->route('certification-facilities.show', ['certification_facility' => $facilityId])
                         ->with('success', 'Certification Level created successfully.');
    }
}
