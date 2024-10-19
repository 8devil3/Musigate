<?php

namespace App\Providers;

use App\Models\Room\Room;
use App\Models\Studio\Studio;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Vite;
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
        Vite::prefetch(concurrency: 3);

        Gate::define('create-update-room', function(User $user, Room $room){
            return $user->id === $room->studio->user_id;
        });

        Gate::define('create-update-studio', function(User $user, Studio $studio){
            return $user->id === $studio->user_id;
        });
    }
}
