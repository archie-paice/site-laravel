<?php

namespace App\Http\Controllers;

use App\Models\CertificationLevel;
use Illuminate\Http\Request;

class CertificationLevelController extends Controller
{
    public function store(Request $request, int $facilityId) {
        $validated = $request->validate([
            'level' => 'required|integer',
            'name' => 'required|string',
            'abbreviation' => 'required|string|max:3',
            'default' => 'nullable|boolean',
        ]);

        $certificationLevel = new CertificationLevel();
        $certificationLevel->facility_id = $facilityId;
        $certificationLevel->name = $validated['name'];
        $certificationLevel->abbreviation = $validated['abbreviation'] ?? null;
        $certificationLevel->level = $validated['level'];
        $certificationLevel->save();

        // Update default certification level if none exists, or if user wants it to be default
        if ($certificationLevel->facility->defaultCertificationLevel->count() == 0 || ($validated['default'] ?? false)) {
            $certificationLevel->facility->default_certification_level_id = $certificationLevel->id;
            $certificationLevel->facility->save();
        }

        return redirect()->route('certification-facilities.show', ['certification_facility' => $facilityId])
                         ->with('success', 'Certification Level created successfully.');
    }
}
