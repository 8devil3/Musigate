<?php

namespace App\Http\Controllers\Backoffice\Studio;

use App\Http\Controllers\Controller;
use App\Models\Studio\Bundle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BundleController extends Controller
{
    public function index(): Response
    {
        $bundles = auth()->user()->studio->bundles;
    
        return Inertia::render('Backoffice/Studio/Bundles/Index', compact('bundles'));
    }

    public function create(): Response
    {
        $bundle = [];

        return Inertia::render('Backoffice/Studio/Bundles/CreateEdit', compact('bundle'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // 'color' => 'required|string|starts_with:#|size:7',
            // 'is_bookable' => 'boolean',
            'is_visible' => 'boolean',
            'description' => 'nullable|string',
        ]);

        $bundle = auth()->user()->studio->bundles()->create($request->toArray());

        return to_route('pacchetti.edit', $bundle->id)->with('success', 'Pacchetto salvato');
    }

    public function edit(Bundle $bundle): Response
    {
        return Inertia::render('Backoffice/Studio/Bundles/CreateEdit', compact('bundle'));
    }

    public function update(Request $request, Bundle $bundle): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // 'color' => 'required|string|starts_with:#|size:7',
            // 'is_bookable' => 'boolean',
            'is_visible' => 'boolean',
            'description' => 'nullable|string',
        ]);

        $bundle->update($request->toArray());

        return back()->with('success', 'Pacchetto salvato');
    }

    public function destroy(Bundle $bundle): RedirectResponse
    {
        $bundle->delete();

        return back()->with('success', 'Pacchetto eliminato');
    }
}
