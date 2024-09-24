<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\CancelPolicySetting;
use App\Models\Studio\Availability;
use App\Models\BookingSetting;
use App\Models\Studio\Contact;
use App\Models\Studio\Location;
use App\Models\Studio\Rule;
use App\Models\Studio\Social;
use App\Models\Studio\Studio;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
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
            'tos' => 'accepted|boolean',
            'privacy' => 'accepted|boolean',
        ]);

        $user = User::create([
            'role_id' => 2,
            'first_name' => ucfirst(strtolower($request->first_name)),
            'last_name' => ucfirst(strtolower($request->last_name)),
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'tos' => $request->tos,
            'privacy' => $request->privacy,
        ]);

        //creo il nuovo studio
        $studio = Studio::create([
            'user_id' => $user->id,
            'name' => ucwords(strtolower($request->studio_name)),
            // 'category' => $request->category,
            // 'vat' => $request->vat,
        ]);

        for ($i=1; $i <= 7; $i++){
            Availability::create([
                'studio_id' => $studio->id,
                'weekday' => $i,
            ]);
        }

        BookingSetting::create(['studio_id' => $studio->id]);
        CancelPolicySetting::create(['studio_id' => $studio->id]);
        Location::create(['studio_id' => $studio->id]);
        Rule::create(['studio_id' => $studio->id]);
        Social::create(['studio_id' => $studio->id]);
        Contact::create(['studio_id' => $studio->id]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
