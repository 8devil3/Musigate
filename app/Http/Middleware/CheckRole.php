<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $role_id = auth()->user()->role_id;

        if ($role === 'superadmin' && $role_id !== Role::SUPERADMIN) abort(403);

        if ($role === 'studio' && $role_id !== Role::STUDIO) abort(403);

        if ($role === 'artist' && $role_id !== Role::ARTIST) abort(403);

        return $next($request);
    }
}
