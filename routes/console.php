<?php

use App\Jobs\SyncRoster;
use App\Jobs\UpdateOnlineControllers;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    SyncRoster::dispatch();
})->everySixHours();

Schedule::call(function () {
    UpdateOnlineControllers::dispatch();
})->everyMinute();