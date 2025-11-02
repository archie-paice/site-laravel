<?php

use function Pest\Stressless\stress;

test('homepage loads', function () {
    $response = $this->get(route('home'));

    $response->assertStatus(200);
    $response->assertViewIs('home');
});

test('homepage can handle stress', function() {
    $response = stress(route('home'))->for(5)->seconds();

    expect($response->requests()->duration()->med())->toBeLessThan(3000);
});
