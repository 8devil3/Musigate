<?php

namespace App\Http\Controllers\Auth\RegisterStudio;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\CreateStudioService;
use App\Services\LocationGeocodeService;
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
        $studio_step1 = session()->get('studio_step1');

        session()->put('registered_as', 'studio');

        return Inertia::render('Auth/Studio/Starter/Register', compact('step', 'studio_step1'));
    }

    public function step_2(Request $request): Response
    {
        if($request->step == 1){
            $request->validate([
                'name' => 'required|string|max:255',
                'category' => 'required|string|in:Home,Professional',
                'vat' => 'nullable|required_if:category,Professional|string|size:11',
            ]);

            session()->forget('studio_step1');
    
            session()->put('studio_step1', [
                'name' => ucwords(strtolower($request->name)),
                'category' => $request->category,
                'vat' => $request->vat,
            ]);
        }

        $studio_step2 = session()->get('studio_step2');

        $step = 2;

        return Inertia::render('Auth/Studio/Starter/Register', compact('step', 'studio_step2'));
    }
    
    public function step_3(Request $request): Response
    {
        $request->validate([
            'notes' => 'nullable|string|max:255',
            'is_manual_address' => 'string|in:true,false'
        ]);

        session()->forget('studio_step2');

        session()->put('studio_step2', LocationGeocodeService::address_data($request));

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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email:rfc,dns|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'tos' => 'accepted',
            'privacy' => 'accepted',
        ]);

        $user = User::create([
            'first_name' => ucfirst(strtolower($request->first_name)),
            'last_name' => ucfirst(strtolower($request->last_name)),
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'tos' => $request->tos,
            'privacy' => $request->privacy,
        ]);

        CreateStudioService::store($user);

        event(new Registered($user));

        session()->forget(['studio_step1', 'studio_step2', 'registered_as']);

        Auth::login($user);

        return to_route('dashboard');
    }
}
