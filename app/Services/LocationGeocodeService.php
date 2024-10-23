<?php

namespace App\Services;

use Spatie\Geocoder\Facades\Geocoder;
use Illuminate\Http\Request;

class LocationGeocodeService
{
    public static function address_data(Request $request): array
    {
        $is_manual_address = filter_var($request->is_manual_address, FILTER_VALIDATE_BOOL);

        $address_data = [
            'complete_address' => null,
            'address' => null,
            'number' => null,
            'cap' => null,
            'city' => null,
            'province' => null,
            'lon' => null,
            'lat' => null,
            'is_manual_address' => $is_manual_address,
        ];

        if($is_manual_address){
            $request->validate([
                'address' => 'required|string|max:255',
                'number' => 'nullable|string|max:8',
                'city' => 'required|string|max:255',
                'province' => 'required|string|max:255',
                'cap' => 'required|string|max:5',
            ]);

            $request_address = $request->only(['address', 'number', 'cap', 'city', 'province']);
            $complete_address = implode(', ', $request_address);
            $geocode = Geocoder::getCoordinatesForAddress($complete_address);

            $address_data['complete_address'] = ucwords(strtolower($complete_address));
            $address_data['address'] = ucwords(strtolower($request->address));
            $address_data['number'] = $request->number ?? null;
            $address_data['city'] = ucwords(strtolower($request->city));
            $address_data['province'] = 'Provincia di ' . ucwords(strtolower($request->province));
            $address_data['cap'] = $request->cap;
            $address_data['lon'] = $geocode['lng'];
            $address_data['lat'] = $geocode['lat'];

        } else {
            $request->validate([
                'complete_address' => 'required|string|max:255',
            ]);

            $geocode = Geocoder::getCoordinatesForAddress($request->complete_address);

            foreach ($geocode['address_components'] as $value) {
                if($value->types[0] === 'route') $address_data['address'] = $value->long_name ?? null;
                if($value->types[0] === 'street_number') $address_data['number'] = $value->long_name ?? null;
                if($value->types[0] === 'locality') $address_data['city'] = $value->long_name ?? null;
                if($value->types[0] === 'administrative_area_level_2') $address_data['province'] = $value->long_name ?? null;
                if($value->types[0] === 'postal_code') $address_data['cap'] = $value->long_name ?? null;
            }

            $address_data['complete_address'] = $request->complete_address;
            $address_data['lon'] = $geocode['lng'];
            $address_data['lat'] = $geocode['lat'];
        }

        return $address_data;
    }
}
