<?php

use App\Jobs\SyncRoster;
use App\Jobs\UpdateOnlineControllers;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(callback: function () {
    SyncRoster::dispatch();
})->everyTwoHours();

Schedule::call(callback: function () {
    UpdateOnlineControllers::dispatch();
})->everyMinute();