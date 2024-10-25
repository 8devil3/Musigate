<?php

namespace App\Http\Controllers\Backoffice\Studio;
use App\Http\Controllers\Controller;
use App\Services\CheckStudioInfo;
use App\Services\LocationGeocodeService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class LocationController extends Controller
{
    public function edit(): Response
    {
        $studio = auth()->user()->studio->load('location');

        return Inertia::render('Backoffice/Studio/Location', compact('studio'));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'notes' => 'nullable|string|max:255',
            'is_manual_address' => 'boolean'
        ]);

        $studio = auth()->user()->studio;

        $studio->location->update(LocationGeocodeService::address_data($request));

        CheckStudioInfo::update_studio($studio);

        return back()->with('success', 'Location salvata');
    }
}
