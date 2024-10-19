<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
            ])
        ->alias([
            'check_role' => \App\Http\Middleware\CheckRole::class,
            'check_studio_info' => \App\Http\Middleware\CheckStudioInfo::class,
            'check_room_owner' => \App\Http\Middleware\CheckRoomOwner::class,
            'google_refresh_token' => \App\Http\Middleware\GoogleRefreshToken::class,
        ]);

        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
