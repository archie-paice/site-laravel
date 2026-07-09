<?php

use App\Models\CertificationFacility;
use App\Models\CertificationLevel;
use App\Models\User;
use App\Models\UserCertification;
use Database\Seeders\PermissionSeeder;

beforeEach(fn () => $this->seed(PermissionSeeder::class));

test('a user profile publicly displays their certifications', function () {
    $facility = CertificationFacility::factory()->create(['identifier' => 'ZJX', 'name' => 'Jacksonville']);
    $level = CertificationLevel::factory()->create(['facility_id' => $facility->id, 'name' => 'Ground', 'abbreviation' => 'GND']);

    $user = User::factory()->create();
    UserCertification::create(['user_id' => $user->id, 'certification_level_id' => $level->id]);

    $response = $this->get(route('users.show', ['user' => $user->id]));

    $response->assertStatus(200);
    $response->assertSee('Certifications');
    $response->assertSee('GND');
});
