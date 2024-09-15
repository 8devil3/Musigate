<?php

namespace App\Http\Middleware;

use Closure;
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
        if(auth()->check()){
            $studio = auth()->user()->studio;

            if(
                $studio->name &&
                $studio->category &&
                $studio->location->complete_address &&
                count($studio->payment_methods) > 0 &&
                count($studio->photos) > 0 &&
                strlen($studio->desc) > 100 &&
                ($studio->category === 'Professional' ? $studio->vat : true) &&
                ($studio->contacts->email || $studio->contacts->phone || $studio->contacts->telegram || $studio->contacts->messenger || $studio->contacts->whatsapp)
            ){
                $studio->update([
                    'is_complete' => true,
                ]);
            } else {
                $studio->update([
                    'hide' => true,
                    'is_complete' => false,
                ]);
            }
        }

        return $next($request);
    }
}
