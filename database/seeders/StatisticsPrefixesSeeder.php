<?php

namespace Database\Seeders;

use App\Models\StatisticsPrefixes;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatisticsPrefixesSeeder extends Seeder
{
    private array $fields = [
        'JAX', 'ZJX', 'MCO', 'DAB', 'TLH', 'PNS', 'CHS', 'LCQ', 'ORL', 'SFB', 'TIX', 'ISM', 'LEE', 'VQQ', 'NIP'
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach($this->fields as $field) {
            StatisticsPrefixes::firstOrCreate([
                'name' => $field
            ]);
        }
    }
}
