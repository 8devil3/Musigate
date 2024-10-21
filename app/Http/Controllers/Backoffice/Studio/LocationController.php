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
            'complete_address' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'number' => 'nullable|string|max:8',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'cap' => 'required|string|max:5',
            'notes' => 'nullable|string|max:255',
            'is_manual_address' => 'boolean',
        ]);

        $complete_address = $request->complete_address;

        if($request->is_manual_address){
            unset($request->toArray()['complete_address']);
            $complete_address = implode($request->toArray());
        }

        $geocode = Geocoder::getCoordinatesForAddress($complete_address);

        auth()->user()->studio->location->update([
            'complete_address' => $complete_address,
            'address' => $request->address,
            'number' => $request->number,
            'city' => $request->city,
            'province' => $request->province,
            'cap' => $request->cap,
            'notes' => $request->notes,
            'lon' => $geocode['lng'],
            'lat' => $geocode['lat']
        ]); 

        return back()->with('success', 'Location salvata');
    }
}
