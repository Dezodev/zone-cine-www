<?php

use App\Jobs\SyncTmdbPopular;
use Illuminate\Support\Facades\Schedule;

// Synchro TMDB chaque nuit à 3h
Schedule::job(new SyncTmdbPopular(pages: 5))->dailyAt('03:00');
