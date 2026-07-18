<?php

namespace App\Providers;

use App\Services\Socialite\VatsimProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /*Socialite::extend('vatsim', function($app) {
            $config = $app['config']['services.vatsim'];
            return Socialite::buildProvider(VatsimProvider::class, $config);
        });*/

        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        $socialite = $this->app->make('Laravel\Socialite\Contracts\Factory');

        $socialite->extend('vatsim', function ($app) use ($socialite) {
            $config = $app['config']['services.vatsim'];

            return $socialite->buildProvider(VatsimProvider::class, $config);
        });
    }
}
