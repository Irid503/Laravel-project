<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/* Лаба 14: каждую минуту отправляем модераторам статистику сайта за день.
   Запуск планировщика: php artisan schedule:run (один раз) или schedule:work (постоянно). */
Schedule::command('stats:send')->everyMinute();
