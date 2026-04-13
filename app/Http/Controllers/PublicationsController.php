<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicationsController extends Controller
{
    public function index()
    {
        // TODO: Replace with Eloquent query once Publication model is built.
        $categories = [
            [
                'slug'        => 'sops',
                'title'       => 'Standard Operating Procedures',
                'description' => 'Facility-wide and position-specific standard operating procedures for vZJX controllers.',
                'icon'        => 'document-text',
                'documents'   => [
                    ['name' => 'ZJX ARTCC SOP',                'version' => 'v2.1', 'updated_at' => '2025-01-15 14:22:00', 'description' => 'General facility standard operating procedures.',                 'file_url' => '#'],
                    ['name' => 'Jacksonville Center (ZJX) SOP', 'version' => 'v3.0', 'updated_at' => '2025-03-10 09:47:00', 'description' => 'En-route center operations at ZJX.',                            'file_url' => '#'],
                    ['name' => 'Jacksonville TRACON (JAX) SOP', 'version' => 'v1.5', 'updated_at' => '2025-02-18 02:47:00', 'description' => 'TRACON approach and departure procedures for KJAX.',             'file_url' => '#'],
                    ['name' => 'Tampa TRACON (TPA) SOP',        'version' => 'v2.2', 'updated_at' => '2024-12-05 21:50:11', 'description' => 'TRACON approach and departure procedures for KTPA.',             'file_url' => '#'],
                    ['name' => 'Orlando TRACON (MCO) SOP',      'version' => 'v1.8', 'updated_at' => '2025-01-22 16:30:00', 'description' => 'TRACON approach and departure procedures for KMCO.',            'file_url' => '#'],
                    ['name' => 'Ground/Local SOP',              'version' => 'v1.2', 'updated_at' => '2024-11-30 11:05:00', 'description' => 'Ground and local control procedures for towered airports.',      'file_url' => '#'],
                ],
            ],
            [
                'slug'        => 'loas',
                'title'       => 'Letters of Agreement',
                'description' => 'Operational agreements between vZJX and adjacent facilities governing coordination procedures.',
                'icon'        => 'document-duplicate',
                'documents'   => [
                    ['name' => 'ZJX / ZMA LOA',        'version' => 'v1.0', 'updated_at' => '2024-10-12 08:00:00', 'description' => 'Agreement between Jacksonville Center and Miami Center.',   'file_url' => '#'],
                    ['name' => 'ZJX / ZTL LOA',        'version' => 'v1.1', 'updated_at' => '2024-09-03 13:15:00', 'description' => 'Agreement between Jacksonville Center and Atlanta Center.', 'file_url' => '#'],
                    ['name' => 'ZJX / JAX TRACON LOA', 'version' => 'v2.0', 'updated_at' => '2025-01-08 17:44:00', 'description' => 'Internal agreement between ZJX center and JAX TRACON.',    'file_url' => '#'],
                    ['name' => 'ZJX / TPA TRACON LOA', 'version' => 'v1.3', 'updated_at' => '2024-08-20 10:22:00', 'description' => 'Internal agreement between ZJX center and TPA TRACON.',    'file_url' => '#'],
                ],
            ],
            [
                'slug'        => 'training',
                'title'       => 'Training Materials',
                'description' => 'Study guides, training syllabi, and reference materials for controller certification.',
                'icon'        => 'academic-cap',
                'documents'   => [
                    ['name' => 'S1 Study Guide',    'version' => 'v3.1', 'updated_at' => '2025-02-01 12:00:00', 'description' => 'Observer to Student 1 training guide for ground and delivery.',       'file_url' => '#'],
                    ['name' => 'S2 Study Guide',    'version' => 'v2.4', 'updated_at' => '2025-02-14 09:30:00', 'description' => 'Student 1 to Student 2 training guide for local control.',            'file_url' => '#'],
                    ['name' => 'S3 Study Guide',    'version' => 'v2.0', 'updated_at' => '2025-03-05 15:10:00', 'description' => 'Student 2 to Student 3 training guide for approach control.',         'file_url' => '#'],
                    ['name' => 'C1 Study Guide',    'version' => 'v1.7', 'updated_at' => '2025-01-28 07:55:00', 'description' => 'Student 3 to Controller 1 training guide for en-route control.',     'file_url' => '#'],
                    ['name' => 'Training Syllabus', 'version' => 'v4.0', 'updated_at' => '2025-03-20 18:00:00', 'description' => 'vZJX full training programme syllabus and milestones.',              'file_url' => '#'],
                ],
            ],
            [
                'slug'        => 'references',
                'title'       => 'Quick Reference Guides',
                'description' => 'Quick reference cards and cheat sheets for use during controlling sessions.',
                'icon'        => 'bookmark',
                'documents'   => [
                    ['name' => 'ZJX Sector Quick Reference', 'version' => 'v1.0', 'updated_at' => '2024-12-11 20:00:00', 'description' => 'Sector boundaries and frequencies at a glance.',        'file_url' => '#'],
                    ['name' => 'Phraseology Reference Card', 'version' => 'v2.1', 'updated_at' => '2024-11-07 06:45:00', 'description' => 'Standard and non-standard ATC phraseology reference.',  'file_url' => '#'],
                    ['name' => 'ATIS/ASOS Reference',        'version' => 'v1.0', 'updated_at' => '2024-10-25 14:30:00', 'description' => 'ATIS construction and weather reporting reference.',    'file_url' => '#'],
                ],
            ],
            [
                'slug'        => 'maps',
                'title'       => 'Facility Maps & Charts',
                'description' => 'Airspace diagrams, sector maps, and facility charts for ZJX ARTCC.',
                'icon'        => 'map',
                'documents'   => [
                    ['name' => 'ZJX Airspace Overview',     'version' => 'v2.0', 'updated_at' => '2025-01-19 11:00:00', 'description' => 'Full ZJX ARTCC airspace diagram with sector boundaries.', 'file_url' => '#'],
                    ['name' => 'High Altitude Sectors Map', 'version' => 'v1.3', 'updated_at' => '2024-12-18 02:47:00', 'description' => 'High altitude (FL240+) sector map for ZJX.',              'file_url' => '#'],
                    ['name' => 'Low Altitude Sectors Map',  'version' => 'v1.5', 'updated_at' => '2024-12-18 03:10:00', 'description' => 'Low altitude (below FL240) sector map for ZJX.',         'file_url' => '#'],
                    ['name' => 'KJAX Airport Diagram',      'version' => 'v1.0', 'updated_at' => '2024-09-14 22:00:00', 'description' => 'Jacksonville International airport surface chart.',        'file_url' => '#'],
                    ['name' => 'KTPA Airport Diagram',      'version' => 'v1.1', 'updated_at' => '2024-09-14 22:15:00', 'description' => 'Tampa International airport surface chart.',              'file_url' => '#'],
                    ['name' => 'KMCO Airport Diagram',      'version' => 'v1.0', 'updated_at' => '2024-09-14 22:30:00', 'description' => 'Orlando International airport surface chart.',            'file_url' => '#'],
                ],
            ],
        ];

        return view('publications.index', compact('categories'));
    }
}
