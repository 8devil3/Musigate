<?php

namespace App\Http\Controllers\Backoffice\Studio;

use App\Http\Controllers\Controller;
use App\Models\Studio\Bundle;
use App\Services\CheckStudioInfo;
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
            'is_published' => 'boolean',
            'description' => 'nullable|string',
        ]);

        $studio = auth()->user()->studio;

        $bundle = $studio->bundles()->create($request->toArray());

        CheckStudioInfo::update_studio($studio);

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
            'is_published' => 'boolean',
            'description' => 'nullable|string',
        ]);

        $bundle->update($request->toArray());

        CheckStudioInfo::update_studio($bundle->studio);

        return back()->with('success', 'Pacchetto salvato');
    }

    public function destroy(Bundle $bundle): RedirectResponse
    {
        $bundle->delete();

        CheckStudioInfo::update_studio($bundle->studio);

        return back()->with('success', 'Pacchetto eliminato');
    }
}
