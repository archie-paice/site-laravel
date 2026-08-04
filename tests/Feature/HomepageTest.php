<?php

test('homepage loads', function () {
    $response = $this->get(route('home'));

    $response->assertStatus(200);
    $response->assertViewIs('home');
});
