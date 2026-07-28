<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Http;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule::call(function () {
//     Http::post('https://bmglbl3.n8n.bocindonesia.com/webhook-test/4ac54138-1d5d-4da0-9967-71758c4c510a', [
//         'event' => 'check_articles',
//         'timestamp' => now()->toDateTimeString()
//     ]);
// })->everyMinute();

Schedule::command('app:trigger-n8n-webhook')->everyMinute();