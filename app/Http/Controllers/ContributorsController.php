<?php

namespace App\Http\Controllers;

use App\Models\ManualContributor;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ContributorsController extends Controller
{
    private function fetchGithubUser(string $login): ?array
    {
        return Cache::remember("github_user_{$login}", 3600, function () use ($login) {
            $r = Http::withHeaders(['Accept' => 'application/vnd.github+json'])
                ->get("https://api.github.com/users/{$login}");
            return $r->ok() ? $r->json() : null;
        });
    }

    private function mapManual(ManualContributor $m): array
    {
        $profile = $m->github_username ? $this->fetchGithubUser($m->github_username) : null;
        return [
            'login'         => $m->github_username,
            'display_name'  => $m->display_name ?? $profile['name'] ?? $m->github_username ?? 'Unknown',
            'html_url'      => $m->github_username ? "https://github.com/{$m->github_username}" : null,
            'contributions' => null,
            'note'          => $m->note,
        ];
    }

    public function index()
    {
        $apiContributors = Cache::remember('github_contributors', 3600, function () {
            $response = Http::withHeaders(['Accept' => 'application/vnd.github+json'])
                ->get('https://api.github.com/repos/zjx-artcc/site-laravel/contributors', ['per_page' => 100]);

            return $response->ok() ? collect($response->json()) : collect();
        });

        $manualLogins = ManualContributor::pluck('github_username');

        $main = $apiContributors->map(function ($c) {
            $profile = $this->fetchGithubUser($c['login']);
            return [
                'login'         => $c['login'],
                'display_name'  => $profile['name'] ?? $c['login'],
                'html_url'      => $c['html_url'],
                'contributions' => $c['contributions'],
                'note'          => null,
            ];
        })->concat(
            ManualContributor::where('section', 'main')->get()->map(fn($m) => $this->mapManual($m))
        )->reject(fn($c) => $manualLogins->contains($c['login']) && !ManualContributor::where(['github_username' => $c['login'], 'section' => 'main'])->exists());

        $fork        = ManualContributor::where('section', 'fork')->get()->map(fn($m) => $this->mapManual($m));
        $contributor = ManualContributor::where('section', 'contributor')->get()->map(fn($m) => $this->mapManual($m));
        $beta        = ManualContributor::where('section', 'beta')->get()->map(fn($m) => $this->mapManual($m));

        return view('contributors.index', compact('main', 'fork', 'contributor', 'beta'));
    }
}
