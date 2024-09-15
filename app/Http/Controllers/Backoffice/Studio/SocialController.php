<?php

namespace App\Http\Controllers\Backoffice\Studio;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class SocialController extends Controller
{
    public function edit(): Response
    {
        $socials = auth()->user()->studio->social;

        return Inertia::render('Backoffice/Studio/Socials', compact('socials'));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'facebook' => 'nullable|url:https|max:255',
            'instagram' =>  'nullable|url:https|max:255',
            'youtube' => 'nullable|url:https|max:255',
            'soundcloud' => 'nullable|url:https|max:255',
            'spotify' => 'nullable|url:https|max:255',
            'itunes' => 'nullable|url:https|max:255',
            'linkedin' => 'nullable|url:https|max:255',
            'website' => 'nullable|url:https|max:255' ,
        ]);

        auth()->user()->studio->social->update($request->toArray());

        return redirect()->back();
    }
}
