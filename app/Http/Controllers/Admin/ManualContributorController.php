<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ManualContributor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ManualContributorController extends Controller
{
    public function index()
    {
        return view('admin.contributors.index', [
            'contributors' => ManualContributor::orderBy('github_username')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'github_username' => 'nullable|string|max:39|unique:manual_contributors,github_username',
            'display_name'    => 'required_without:github_username|nullable|string|max:100',
            'section'         => 'required|in:main,fork,contributor,beta',
            'note'            => 'nullable|string|max:100',
        ]);

        ManualContributor::create($validated);
        Cache::forget('github_contributors');

        return redirect()->back()->with('success', "@{$validated['github_username']} added as a contributor.");
    }

    public function destroy(ManualContributor $contributor)
    {
        $contributor->delete();
        Cache::forget('github_contributors');

        return redirect()->back()->with('success', "@{$contributor->github_username} removed.");
    }
}
