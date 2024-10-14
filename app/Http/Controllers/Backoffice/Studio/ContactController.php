<?php

namespace App\Http\Controllers\Backoffice\Studio;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ContactController extends Controller
{
    public function edit(): Response
    {
        $contacts = auth()->user()->studio->contacts;
        
        return Inertia::render('Backoffice/Studio/Contacts', compact('contacts'));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'nullable|email|max:255',
            'phone' =>  'nullable|string|max:255',
            'telegram' => 'nullable|url:https|max:255',
            'messenger' => 'nullable|url:https|max:255',
            'whatsapp' => 'nullable|url:https|max:255',
        ]);

        auth()->user()->studio->contacts->update($request->toArray());

        return back()->with('success', 'Contatti salvati');
    }
}
