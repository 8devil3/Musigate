<?php

namespace App\Http\Controllers\Auth\RegisterStudio;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\CreateStudioService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Geocoder\Facades\Geocoder;

class StarterController extends Controller
{
    public function step_1(): Response
    {
        $step = 1;
        $data_step1 = session()->get('data_step1');

        return Inertia::render('Auth/Studio/Starter/Register', compact('step', 'data_step1'));
    }

    public function step_2(Request $request): Response
    {
        if($request->step == 1){
            $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
            ]);
    
            session()->put('data_step1', [
                'first_name' => ucwords($request->first_name),
                'last_name' => ucwords($request->last_name),
            ]);
        }

        $data_step2 = session()->get('data_step2');

        $step = 2;

        return Inertia::render('Auth/Studio/Starter/Register', compact('step', 'data_step2'));
    }
    
    public function step_3(Request $request): Response
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

        session()->put('data_step2', [
            'name' => ucwords($request->name),
            'category' => $request->category,
            'vat' => $request->vat,
            'complete_address' => $geocode['formatted_address'],
            'address' => $geocode_address['address'],
            'number' => $geocode_address['number'] ?? null,
            'city' => $geocode_address['city'],
            'province' => $geocode_address['province'],
            'cap' => $geocode_address['cap'],
            'lon' => $geocode['lng'],
            'lat' => $geocode['lat'],
            'is_manual_address' => $request->is_manual_address,
        ]);

        $step = 3;

        return Inertia::render('Auth/Studio/Starter/Register', compact('step'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|string|lowercase|email:rfc,dns|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'tos' => 'accepted',
            'privacy' => 'accepted',
        ]);

        $data_step1 = session()->get('data_step1');

        $user = User::create([
            'first_name' => ucfirst(strtolower($data_step1['first_name'])),
            'last_name' => ucfirst(strtolower($data_step1['last_name'])),
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'tos' => $request->tos,
            'privacy' => $request->privacy,
        ]);

        CreateStudioService::store($user);
        
        session()->forget(['data_step1', 'data_step2']);

        session()->regenerate();

        event(new Registered($user));

        Auth::login($user);

        return to_route('dashboard');
    }
}
