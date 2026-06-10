<?php

namespace App\Http\Controllers;

use App\Models\CertificationLevel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CertificationLevelController extends Controller
{
    public function store(Request $request, int $facilityId)
    {
        $validated = $request->validate([
            'level' => [
                'required',
                'integer',
                'min:0',
                Rule::unique('certification_levels', 'level')
                    ->where(fn ($query) => $query->where('facility_id', $facilityId)),
            ],
            'name' => 'required|string',
            'abbreviation' => 'required|string|max:3',
        ]);

        $certificationLevel = new CertificationLevel();
        $certificationLevel->facility_id = $facilityId;
        $certificationLevel->name = $validated['name'];
        $certificationLevel->abbreviation = $validated['abbreviation'] ?? null;
        $certificationLevel->level = $validated['level'];
        $certificationLevel->save();

        return redirect()
            ->route('certification-facilities.show', ['facility' => $facilityId])
            ->with('success', 'Certification level created successfully.');
    }
}
