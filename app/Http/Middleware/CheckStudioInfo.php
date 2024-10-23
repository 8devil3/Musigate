<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Role;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckStudioInfo
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(auth()->check() && auth()->user()->role_id === Role::STUDIO){
            $studio = auth()->user()->studio;

            if(
                $studio->name
                && (count($studio->rooms) > 0 || count($studio->bundles) > 0)
                && ($studio->category === 'Professional' ? $studio->vat : true)
                && $studio->availability()->where('open_type', 'close')->count() < 7
                && $studio->location->complete_address
                && count($studio->payment_methods) > 0
                && count($studio->photos) > 0
                && strlen($studio->description) > 100
                && ($studio->contacts->email || $studio->contacts->phone || $studio->contacts->telegram || $studio->contacts->messenger || $studio->contacts->whatsapp)
            ){
                $studio->update([
                    'is_complete' => true,
                ]);
            } else {
                $studio->update([
                    'is_complete' => false,
                    'is_visible' => false,
                ]);
            }
        }

        return $next($request);
    }
}
