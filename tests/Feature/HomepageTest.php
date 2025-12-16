<?php

use function Pest\Stressless\stress;

test('homepage loads', function () {
    $response = $this->get(route('home'));

    $response->assertStatus(200);
    $response->assertViewIs('home');
});
