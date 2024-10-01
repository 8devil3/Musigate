<?php

namespace App\Http\Controllers\Auth;

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
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredStudioController extends Controller
{
    public function create_step_1(): Response
    {
        $step = 1;
        $request = session()->get('studio_data');

        return Inertia::render('Auth/RegisterStudio', compact('step', 'request'));
    }
    
    public function create_step_2(Request $request): Response
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'category' => 'required|string|in:Home,Professional',
            'vat' => 'nullable|required_if:category,Professional|string|size:11',
        ]);

        $step = 2;
        $request = $request->toArray();

        session()->put('studio_data', $request);

        return Inertia::render('Auth/RegisterStudio', compact('step', 'request'));
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

        $user = User::create([
            'role_id' => 2,
            'first_name' => ucfirst(strtolower($request->first_name)),
            'last_name' => ucfirst(strtolower($request->last_name)),
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'tos' => $request->tos,
            'privacy' => $request->privacy,
        ]);

        $this->store_new_studio($user, $request);

        event(new Registered($user));

        Auth::login($user);

        session()->flush();

        return to_route('dashboard');
    }

    public function store_new_studio(User $user, $studio_data)
    {
        $studio = Studio::create([
            'user_id' => $user->id,
            'name' => ucwords(strtolower($studio_data['name'])),
            'category' => $studio_data['category'],
            'vat' => $studio_data['vat'],
        ]);

        for ($i = 1; $i <= 7; $i++){
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
    }
}
