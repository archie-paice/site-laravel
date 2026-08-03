<?php

use App\Models\ManualContributor;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Cache::flush();

    Http::fake([
        'api.github.com/repos/*' => Http::response([
            ['login' => 'alice', 'html_url' => 'https://github.com/alice', 'contributions' => 10, 'type' => 'User'],
            ['login' => 'bob',   'html_url' => 'https://github.com/bob',   'contributions' => 5,  'type' => 'User'],
        ], 200),
        'api.github.com/users/*' => Http::response(['name' => 'A Contributor'], 200),
    ]);
});

test('manual contributors in other sections are de-duplicated out of main', function () {
    // bob is also listed manually under the "fork" section.
    ManualContributor::create(['github_username' => 'bob', 'section' => 'fork']);

    $response = $this->get(route('contributors.index'))->assertOk();

    $main = collect($response->viewData('main'))->pluck('login');
    $fork = collect($response->viewData('fork'))->pluck('login');

    expect($main)->toContain('alice')
        ->and($main)->not->toContain('bob')
        ->and($fork)->toContain('bob');
});

test('github profiles are fetched once each, not per contributor', function () {
    ManualContributor::create(['github_username' => 'bob', 'section' => 'fork']);

    $this->get(route('contributors.index'))->assertOk();

    // 1 contributors list + 2 unique user profiles (alice, bob). No per-row refetch.
    Http::assertSentCount(3);
});
