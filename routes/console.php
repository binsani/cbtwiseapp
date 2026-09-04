<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Downgrade expired premium users — runs daily, no overlapping
Schedule::command('cbtwise:downgrade-expired')
    ->daily()
    ->withoutOverlapping()
    ->runInBackground()
    ->timezone('Africa/Lagos');

// Generate AI-powered SEO pages — runs weekly (Sunday 02:00 WAT, low traffic)
Schedule::command('cbtwise:generate-seo-pages')
    ->weeklyOn(0, '02:00')
    ->withoutOverlapping()
    ->runInBackground()
    ->timezone('Africa/Lagos');

// Nightly ETL — runs every night at midnight WAT
Schedule::command('cbtwise:run-nightly-etl')
    ->dailyAt('00:00')
    ->withoutOverlapping()
    ->runInBackground()
    ->timezone('Africa/Lagos');
