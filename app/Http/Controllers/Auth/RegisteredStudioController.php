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
    public function step_1(): Response
    {
        $step = 1;
        $studio_data = session()->get('studio_data.step1');

        return Inertia::render('Auth/RegisterStudio', compact('step', 'studio_data'));
    }

    public function step_2(Request $request): Response
    {
        if($request->step == 1){
            $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
            ]);
    
            session()->put('studio_data.step1', [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
            ]);
        }

        $studio_data = session()->get('studio_data.step2');

        $step = 2;

        return Inertia::render('Auth/RegisterStudio', compact('step', 'studio_data'));
    }
    
    public function step_3(Request $request): Response
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|in:Home,Professional',
            'vat' => 'nullable|required_if:category,Professional|string|size:11',
        ]);

        session()->put('studio_data.step2', [
            'name' => $request->name,
            'category' => $request->category,
            'vat' => $request->vat,
        ]);

        $step = 3;

        return Inertia::render('Auth/RegisterStudio', compact('step'));
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

        $studio_data = session()->get('studio_data');

        $user = User::create([
            'first_name' => ucfirst(strtolower($studio_data['step1']['first_name'])),
            'last_name' => ucfirst(strtolower($studio_data['step1']['last_name'])),
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'tos' => $request->tos,
            'privacy' => $request->privacy,
        ]);

        $this->store_new_studio($user, $studio_data);

        event(new Registered($user));
        
        session()->flush();

        Auth::login($user);

        return to_route('dashboard');
    }

    public function store_new_studio(User $user, array $studio_data)
    {
        $studio = Studio::create([
            'user_id' => $user->id,
            'name' => ucwords(strtolower($studio_data['step2']['name'])),
            'category' => $studio_data['step2']['category'],
            'vat' => $studio_data['step2']['vat'],
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
