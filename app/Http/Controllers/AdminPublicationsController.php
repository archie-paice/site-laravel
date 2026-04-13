<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminPublicationsController extends Controller
{
    // TODO: Replace static data with Eloquent queries once Publication model is built.

    private function categories(): array
    {
        return [
            'sops'       => 'Standard Operating Procedures',
            'loas'       => 'Letters of Agreement',
            'training'   => 'Training Materials',
            'references' => 'Quick Reference Guides',
            'maps'       => 'Facility Maps & Charts',
        ];
    }

    private function allDocuments(): array
    {
        // TODO: Replace with Publication::orderBy('category')->orderBy('name')->get()
        return [
            ['id' => 1,  'category' => 'sops',       'name' => 'ZJX ARTCC SOP',                'version' => 'v2.1', 'updated_at' => '2025-01-15 14:22:00', 'description' => 'General facility standard operating procedures.',                 'file_url' => '#'],
            ['id' => 2,  'category' => 'sops',       'name' => 'Jacksonville Center (ZJX) SOP', 'version' => 'v3.0', 'updated_at' => '2025-03-10 09:47:00', 'description' => 'En-route center operations at ZJX.',                            'file_url' => '#'],
            ['id' => 3,  'category' => 'sops',       'name' => 'Jacksonville TRACON (JAX) SOP', 'version' => 'v1.5', 'updated_at' => '2025-02-18 02:47:00', 'description' => 'TRACON approach and departure procedures for KJAX.',             'file_url' => '#'],
            ['id' => 4,  'category' => 'sops',       'name' => 'Tampa TRACON (TPA) SOP',        'version' => 'v2.2', 'updated_at' => '2024-12-05 21:50:11', 'description' => 'TRACON approach and departure procedures for KTPA.',             'file_url' => '#'],
            ['id' => 5,  'category' => 'sops',       'name' => 'Orlando TRACON (MCO) SOP',      'version' => 'v1.8', 'updated_at' => '2025-01-22 16:30:00', 'description' => 'TRACON approach and departure procedures for KMCO.',            'file_url' => '#'],
            ['id' => 6,  'category' => 'sops',       'name' => 'Ground/Local SOP',              'version' => 'v1.2', 'updated_at' => '2024-11-30 11:05:00', 'description' => 'Ground and local control procedures for towered airports.',      'file_url' => '#'],
            ['id' => 7,  'category' => 'loas',       'name' => 'ZJX / ZMA LOA',                 'version' => 'v1.0', 'updated_at' => '2024-10-12 08:00:00', 'description' => 'Agreement between Jacksonville Center and Miami Center.',        'file_url' => '#'],
            ['id' => 8,  'category' => 'loas',       'name' => 'ZJX / ZTL LOA',                 'version' => 'v1.1', 'updated_at' => '2024-09-03 13:15:00', 'description' => 'Agreement between Jacksonville Center and Atlanta Center.',      'file_url' => '#'],
            ['id' => 9,  'category' => 'loas',       'name' => 'ZJX / JAX TRACON LOA',          'version' => 'v2.0', 'updated_at' => '2025-01-08 17:44:00', 'description' => 'Internal agreement between ZJX center and JAX TRACON.',         'file_url' => '#'],
            ['id' => 10, 'category' => 'loas',       'name' => 'ZJX / TPA TRACON LOA',          'version' => 'v1.3', 'updated_at' => '2024-08-20 10:22:00', 'description' => 'Internal agreement between ZJX center and TPA TRACON.',         'file_url' => '#'],
            ['id' => 11, 'category' => 'training',   'name' => 'S1 Study Guide',                'version' => 'v3.1', 'updated_at' => '2025-02-01 12:00:00', 'description' => 'Observer to Student 1 training guide for ground and delivery.',  'file_url' => '#'],
            ['id' => 12, 'category' => 'training',   'name' => 'S2 Study Guide',                'version' => 'v2.4', 'updated_at' => '2025-02-14 09:30:00', 'description' => 'Student 1 to Student 2 training guide for local control.',       'file_url' => '#'],
            ['id' => 13, 'category' => 'training',   'name' => 'S3 Study Guide',                'version' => 'v2.0', 'updated_at' => '2025-03-05 15:10:00', 'description' => 'Student 2 to Student 3 training guide for approach control.',    'file_url' => '#'],
            ['id' => 14, 'category' => 'training',   'name' => 'C1 Study Guide',                'version' => 'v1.7', 'updated_at' => '2025-01-28 07:55:00', 'description' => 'Student 3 to Controller 1 training guide for en-route control.', 'file_url' => '#'],
            ['id' => 15, 'category' => 'training',   'name' => 'Training Syllabus',             'version' => 'v4.0', 'updated_at' => '2025-03-20 18:00:00', 'description' => 'vZJX full training programme syllabus and milestones.',         'file_url' => '#'],
            ['id' => 16, 'category' => 'references', 'name' => 'ZJX Sector Quick Reference',    'version' => 'v1.0', 'updated_at' => '2024-12-11 20:00:00', 'description' => 'Sector boundaries and frequencies at a glance.',                'file_url' => '#'],
            ['id' => 17, 'category' => 'references', 'name' => 'Phraseology Reference Card',    'version' => 'v2.1', 'updated_at' => '2024-11-07 06:45:00', 'description' => 'Standard and non-standard ATC phraseology reference.',          'file_url' => '#'],
            ['id' => 18, 'category' => 'references', 'name' => 'ATIS/ASOS Reference',           'version' => 'v1.0', 'updated_at' => '2024-10-25 14:30:00', 'description' => 'ATIS construction and weather reporting reference.',            'file_url' => '#'],
            ['id' => 19, 'category' => 'maps',       'name' => 'ZJX Airspace Overview',         'version' => 'v2.0', 'updated_at' => '2025-01-19 11:00:00', 'description' => 'Full ZJX ARTCC airspace diagram with sector boundaries.',       'file_url' => '#'],
            ['id' => 20, 'category' => 'maps',       'name' => 'High Altitude Sectors Map',     'version' => 'v1.3', 'updated_at' => '2024-12-18 02:47:00', 'description' => 'High altitude (FL240+) sector map for ZJX.',                   'file_url' => '#'],
            ['id' => 21, 'category' => 'maps',       'name' => 'Low Altitude Sectors Map',      'version' => 'v1.5', 'updated_at' => '2024-12-18 03:10:00', 'description' => 'Low altitude (below FL240) sector map for ZJX.',               'file_url' => '#'],
            ['id' => 22, 'category' => 'maps',       'name' => 'KJAX Airport Diagram',          'version' => 'v1.0', 'updated_at' => '2024-09-14 22:00:00', 'description' => 'Jacksonville International airport surface chart.',             'file_url' => '#'],
            ['id' => 23, 'category' => 'maps',       'name' => 'KTPA Airport Diagram',          'version' => 'v1.1', 'updated_at' => '2024-09-14 22:15:00', 'description' => 'Tampa International airport surface chart.',                   'file_url' => '#'],
            ['id' => 24, 'category' => 'maps',       'name' => 'KMCO Airport Diagram',          'version' => 'v1.0', 'updated_at' => '2024-09-14 22:30:00', 'description' => 'Orlando International airport surface chart.',                 'file_url' => '#'],
        ];
    }

    public function index()
    {
        $documents  = $this->allDocuments();
        $categories = $this->categories();

        return view('admin.publications.index', compact('documents', 'categories'));
    }

    public function create()
    {
        $categories = $this->categories();

        return view('admin.publications.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // TODO: Validate and persist to database
        return redirect()->route('admin.publications.index')
            ->with('success', 'Document created successfully.');
    }

    public function edit(int $id)
    {
        $documents  = $this->allDocuments();
        $categories = $this->categories();

        $document = collect($documents)->firstWhere('id', $id);

        if (!$document) {
            abort(404);
        }

        return view('admin.publications.edit', compact('document', 'categories'));
    }

    public function update(Request $request, int $id)
    {
        // TODO: Validate and update in database
        return redirect()->route('admin.publications.index')
            ->with('success', 'Document updated successfully.');
    }

    public function destroy(int $id)
    {
        // TODO: Delete from database
        return redirect()->route('admin.publications.index')
            ->with('success', 'Document deleted successfully.');
    }
}
