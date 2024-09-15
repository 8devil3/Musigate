<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Suggestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SuggestionController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Backoffice/Suggestions');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $user = auth()->user();

        Suggestion::create([
            'user_id' => $user->id,
            'user_name' => $user->first_name . ' ' . $user->last_name,
            'studio_id' => $user->studio->id,
            'studio_name' => $user->studio->name,
            'title' => $request->title,
            'message' => $request->message,
        ]);

        //TODO: aggiungere notifica email all'amministrazione

        return redirect()->back();
    }
}
