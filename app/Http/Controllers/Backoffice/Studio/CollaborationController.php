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
        $collaborations = auth()->user()->studio->collaborations()->orderByDesc('year')->orderByDesc('month')->get();

        return Inertia::render('Backoffice/Studio/Collaborations/Index', compact('collaborations'));
    }

    public function create(): Response
    {
        $collaboration = [];

        return Inertia::render('Backoffice/Studio/Collaborations/CreateEdit', compact('collaboration'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'month' => ['required', 'integer', 'min:1', 'max:12'],
            'year' => ['required', 'integer', 'min:1900', 'max:' . now()->year],
            'description' => ['nullable', 'string'],
            'spotify' => ['nullable', 'url:https'],
            'soundcloud' => ['nullable', 'url:https'],
            'itunes' => ['nullable', 'url:https'],
            'save_and_new' => ['boolean'],
        ]);

        $collaboration = auth()->user()->studio->collaborations()->create($request->toArray());

        if($request->save_and_new) return to_route('studio.collaborazioni.create')->with('success', 'Collaborazione salvata');

        return to_route('studio.collaborazioni.edit', $collaboration->id)->with('success', 'Collaborazione salvata');
    }

    public function edit(Collaboration $collaboration): Response
    {
        return Inertia::render('Backoffice/Studio/Collaborations/CreateEdit', compact('collaboration'));
    }

    public function update(Request $request, Collaboration $collaboration): RedirectResponse
    {
        if($collaboration->studio->user->id !== auth()->id()) abort(403);

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'month' => ['required', 'integer', 'min:1', 'max:12'],
            'year' => ['required', 'integer', 'min:1900', 'max:' . now()->year],
            'description' => ['nullable', 'string'],
            'spotify' => ['nullable', 'url:https'],
            'soundcloud' => ['nullable', 'url:https'],
            'itunes' => ['nullable', 'url:https'],
        ]);

        $collaboration->update($request->toArray());

        return back()->with('success', 'Collaborazione aggiornata');
    }

    public function destroy(Collaboration $collaboration): RedirectResponse
    {
        if($collaboration->studio->user->id !== auth()->id()) abort(403);

        $collaboration->delete();

        return back()->with('success', 'Collaborazione eliminata');
    }
}
