<?php

namespace App\Http\Controllers\Auth\RegisterArtist;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\BookingSetting;
use App\Models\Studio\Contact;
use App\Models\Studio\Location;
use App\Models\Studio\Rule;
use App\Models\Studio\Social;
use App\Models\Studio\Studio;
use App\Models\Studio\CancelPolicySetting;
use App\Models\Studio\Availability;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredArtistController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Artist/Register');
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
            'role_id' => 3,
            'first_name' => ucfirst(strtolower($request->first_name)),
            'last_name' => ucfirst(strtolower($request->last_name)),
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'tos' => $request->tos,
            'privacy' => $request->privacy,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return to_route('dashboard');
    }
}
