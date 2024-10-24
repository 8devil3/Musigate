<?php

use App\Jobs\RemoveNotVerifiedUsersJob;
use Illuminate\Support\Facades\Schedule;

//eliminazione account con email non verificata
Schedule::job(new RemoveNotVerifiedUsersJob())->dailyAt('23:30');
