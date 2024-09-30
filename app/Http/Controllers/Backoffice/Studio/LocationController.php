<?php

namespace App\Http\Controllers\Backoffice\Studio;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Geocoder\Facades\Geocoder;

class LocationController extends Controller
{
    public function edit(): Response
    {
        $location = auth()->user()->studio->location;

        return Inertia::render('Backoffice/Studio/Location', compact('location'));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'address' => 'required|string|max:255',
            'number' => 'nullable|string|max:8',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'cap' => 'required|string|max:5',
            'notes' => 'nullable|string|max:255'
        ]);

        $address = [
            $request->address,
            $request->number,
            $request->city
        ];

        $geocode = Geocoder::getCoordinatesForAddress(implode(' ', $address));

        auth()->user()->studio->location->update(array_merge($request->toArray(), [
            'complete_address' => $geocode['formatted_address'],
            'lon' => $geocode['lng'],
            'lat' => $geocode['lat']
        ]));

        return back()->with('success', 'Salvato');
    }
}
