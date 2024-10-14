<?php

namespace App\Http\Controllers\Backoffice\Studio;

use App\Http\Controllers\Controller;
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

    public function update(Request $request, int $collaboration_id): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'desc' => 'nullable|string',
        ]);

        auth()->user()->studio->collaborations()->findOrFail($collaboration_id)->update($request->toArray());

        return back()->with('success', 'Collaborazione aggiornata');
    }

    public function delete(int $collaboration_id): RedirectResponse
    {
        auth()->user()->studio->collaborations()->findOrFail($collaboration_id)->delete();

        return back()->with('success', 'Collaborazione eliminata');
    }
}
