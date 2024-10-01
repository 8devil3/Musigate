<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = null;
        $studio = null;

        if(auth()->check() && auth()->user()->role_id === Role::STUDIO){
            $user = auth()->user();
            $studio = $user->studio->only('name', 'vat', 'logo', 'category');
        }

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
                'studio' => $studio,
            ],
            'flash' => [
                'success' => fn() => session('success'),
                'error' => fn() => session('error'),
            ]
        ];
    }
}
