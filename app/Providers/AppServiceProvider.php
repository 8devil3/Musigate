<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Room\Room;
use App\Models\Studio\Bundle;
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

        Gate::define('crud-room', function(User $user, Room $room){
            return $user->id === $room->studio->user_id;
        });

        Gate::define('crud-bundle', function(User $user, Bundle $bundle){
            return $user->id === $bundle->studio->user_id;
        });
    }
}
