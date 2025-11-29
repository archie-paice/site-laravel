<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\SoloCert;
use App\Services\VatusaSoloCertService;
use Illuminate\Http\Request;

class SoloCertController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:255',
        ]);

        $query = $request->input('search');
        $soloCerts = SoloCert::search($query)->paginate(25);

        return view('solo-certs.index', compact('soloCerts'));
    }

    public function show(int $id) {
        $soloCert = SoloCert::findOrFail($id);
    }

    public function create() {
        return view('solo-certs.create');
    }

    public function store(int $id, Request $request, VatusaSoloCertService $vatusaSoloCertService) {
        $validated = $request->validate([
            'position' => ['required', 'regex:/^([A-Z]{2,3})(_([A-Z]{1,3}))?_(DEL|GND|TWR|APP|DEP|CTR)$/'],
            'expires' => 'required|date',
        ]);

        $soloCert = SoloCert::create([
            'user_id' => $id,
            'issued_by_id' => auth()->user()->id,
            'position' => $validated['position'],
            'expires' => $validated['expires']
        ]);

        $vatusaSoloCertService->createVatusaSoloCert($soloCert);
    }

    public function destroy(int $id, VatusaSoloCertService $vatusaSoloCertService) {
        $soloCert = SoloCert::findOrFail($id);

        $vatusaSoloCertService->deleteVatusaSoloCert($soloCert);

        $soloCert->delete();
    }
}
