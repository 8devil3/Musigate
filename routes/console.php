<?php

use App\Jobs\RemoveNotVerifiedUsersJob;
use App\Jobs\SitemapJob;
use Illuminate\Support\Facades\Schedule;

//eliminazione account con email non verificata
Schedule::job(new RemoveNotVerifiedUsersJob())->dailyAt('23:30');

//creo la sitemap
Schedule::job(new SitemapJob())->dailyAt('20:00');
