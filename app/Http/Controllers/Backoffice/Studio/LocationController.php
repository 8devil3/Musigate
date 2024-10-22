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
            'name' => 'required|string|max:255',
            'category' => 'required|string|in:Home,Professional',
            'vat' => 'nullable|required_if:category,Professional|string|size:11',
            'notes' => 'nullable|string|max:255',
            'is_manual_address' => 'string|in:true,false'
        ]);

        $is_manual_address = $request->is_manual_address === 'true' ? true : false;

        if($is_manual_address){
            $request->validate([
                'address' => 'required|string|max:255',
                'number' => 'nullable|string|max:8',
                'city' => 'required|string|max:255',
                'province' => 'required|string|max:255',
                'cap' => 'required|string|max:5',
            ]);

            $arr_complete_address = [];
            foreach ($request->except(['complete_address', 'notes', 'is_manual_address']) as $value) {
                if($value) $arr_complete_address[] = $value;
            }

            $complete_address = implode(', ', $arr_complete_address);
        } else {
            $request->validate([
                'complete_address' => 'required|string|max:255',
            ]);

            $complete_address = $request->complete_address;
        }

        $geocode = Geocoder::getCoordinatesForAddress($complete_address);

        $geocode_address = [];
        foreach ($geocode['address_components'] as $value) {
            if($value->types[0] === 'route') $geocode_address['address'] = $value->long_name ?? null;
            if($value->types[0] === 'street_number') $geocode_address['number'] = $value->long_name ?? null;
            if($value->types[0] === 'locality') $geocode_address['city'] = $value->long_name ?? null;
            if($value->types[0] === 'administrative_area_level_2') $geocode_address['province'] = $value->long_name ?? null;
            if($value->types[0] === 'postal_code') $geocode_address['cap'] = $value->long_name ?? null;
        }

        auth()->user()->studio->location->update([
            'complete_address' => $geocode['formatted_address'],
            'address' => $geocode_address['address'],
            'number' => $geocode_address['number'],
            'city' => $geocode_address['city'],
            'province' => $geocode_address['province'],
            'cap' => $geocode_address['cap'],
            'notes' => $request->notes,
            'lon' => $geocode['lng'],
            'lat' => $geocode['lat']
        ]); 

        return back()->with('success', 'Location salvata');
    }
}
