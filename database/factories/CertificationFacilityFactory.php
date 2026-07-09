<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CertificationFacility>
 */
class CertificationFacilityFactory extends Factory
{
    public function definition(): array
    {
        return [
            'identifier' => strtoupper(fake()->unique()->lexify('???')),
            'name' => fake()->unique()->company(),
            'order' => fake()->numberBetween(0, 100),
        ];
    }
}
