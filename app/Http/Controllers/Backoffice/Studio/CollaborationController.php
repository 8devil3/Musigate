<?php

namespace App\Http\Controllers\Backoffice\Studio;

use App\Http\Controllers\Controller;
use App\Models\Studio\Collaboration;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class CollaborationController extends Controller
{
    public function index(): Response
    {
        $collaborations = auth()->user()->studio->collaborations;

        return Inertia::render('Backoffice/Studio/Collaborations', compact('collaborations'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'desc' => 'nullable|string',
        ]);

        auth()->user()->studio->collaborations()->create($request->toArray());

        return back()->with('success', 'Collaborazione salvata');
    }

    public function update(Request $request, Collaboration $collaboration): RedirectResponse
    {
        if($collaboration->studio->user->id !== auth()->id()) abort(403);

        $request->validate([
            'title' => 'required|string|max:255',
            'desc' => 'nullable|string',
        ]);

        $collaboration->update($request->toArray());

        return back()->with('success', 'Collaborazione aggiornata');
    }

    public function delete(Collaboration $collaboration): RedirectResponse
    {
        if($collaboration->studio->user->id !== auth()->id()) abort(403);

        $collaboration->delete();

        return back()->with('success', 'Collaborazione eliminata');
    }
}
