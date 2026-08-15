<?php

namespace Database\Seeders;

use App\Models\PublicationCategory;
use Illuminate\Database\Seeder;

class PublicationCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Standard Operating Procedures',
            'Letters of Agreement',
            'Training Materials',
            'Quick Reference Guides',
            'Facility Maps & Charts',
        ];

        foreach ($categories as $order => $title) {
            PublicationCategory::firstOrCreate(
                ['title' => $title],
                [
                    'description' => null,
                    'display_order' => $order,
                ],
            );
        }
    }
}
