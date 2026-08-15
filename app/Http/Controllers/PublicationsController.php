<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use App\Models\PublicationCategory;
use Illuminate\Support\Facades\Storage;

class PublicationsController extends Controller
{
    public function index()
    {
        $categories = PublicationCategory::with(['publications' => function ($query) {
            $query->orderBy('name');
        }])
            ->orderBy('display_order')
            ->orderBy('title')
            ->get();

        return view('publications.index', compact('categories'));
    }

    public function file(Publication $publication)
    {
        abort_unless(
            $publication->file_path && Storage::disk('public')->exists($publication->file_path),
            404
        );

        // Anything a browser cannot render (vATIS profile JSON, legacy uploads) is
        // saved to disk rather than dumped on screen as raw text.
        if ($publication->opensInBrowser()) {
            return Storage::disk('public')->response($publication->file_path, $publication->original_filename);
        }

        return Storage::disk('public')->download($publication->file_path, $publication->original_filename);
    }
}
