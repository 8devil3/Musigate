<?php

namespace App\Http\Controllers\Auth\RegisterStudio;

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
use App\Services\CreateStudioService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class StarterController extends Controller
{
    public function step_1(): Response
    {
        $step = 1;
        $studio_data = session()->get('studio_data.step1');

        return Inertia::render('Auth/Studio/Starter/Register', compact('step', 'studio_data'));
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

        return Inertia::render('Auth/Studio/Starter/Register', compact('step', 'studio_data'));
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

        $studio_data = session()->get('studio_data');

        $user = User::create([
            'first_name' => ucfirst(strtolower($studio_data['step1']['first_name'])),
            'last_name' => ucfirst(strtolower($studio_data['step1']['last_name'])),
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'tos' => $request->tos,
            'privacy' => $request->privacy,
        ]);

        CreateStudioService::store($user, $studio_data['step2']['name'], $studio_data['step2']['category'], $studio_data['step2']['vat']);

        event(new Registered($user));
        
        session()->flush();

        Auth::login($user);

        return to_route('dashboard');
    }
}
