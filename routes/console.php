<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Auto-mark absent employees daily at 9:00 AM
Schedule::command('app:mark-absent-employees')->dailyAt('09:00');

// Check for expiring contracts daily at 8:00 AM
Schedule::command('contracts:check-expiry')->dailyAt('08:00');
