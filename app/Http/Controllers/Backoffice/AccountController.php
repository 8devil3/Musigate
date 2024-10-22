<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Notifications\DeleteAccountNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Intervention\Image\Laravel\Facades\Image;

class AccountController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        $user = auth()->user();
        $has_password = $user->getAuthPassword() ? true : false;
        $has_google = $user->google_id && $user->google_token ? true : false;
        $mustVerifyEmail = $request->user() instanceof MustVerifyEmail;
        $status = session('status');
        $user = $user->only(['email', 'first_name', 'last_name', 'google_id', 'google_token']);

        return Inertia::render('Backoffice/Account/Edit', compact('mustVerifyEmail', 'status', 'user', 'has_password', 'has_google'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        if($request->email) {
            $request->validate([
                'email' => ['email', 'max:255', Rule::unique(User::class)->ignore($request->user()->id)],
            ]);
            
            auth()->user()->update([
                'email' => $request->email,
                'email_verified_at' => null,
            ]);
        }

        if($request->first_name || $request->last_name){
            $request->validate([
                'first_name' => ['string', 'max:255'],
                'last_name' => ['string', 'max:255'],
            ]);

            auth()->user()->update([
                'first_name' => ucfirst(strtolower($request->first_name)),
                'last_name' => ucfirst(strtolower($request->last_name)),
            ]);
        }

        return back()->with('success', 'Salvato');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // $request->validate([
        //     'password' => ['required', 'current_password'],
        // ]);

        $user = $request->user();

        if($user->google_id && $user->google_token){
            $google_client = new \Google\Client();
            $google_client->revokeToken($user->google_token);
        }

        if($user->role_id === Role::STUDIO){
            Storage::disk('public')->deleteDirectory('studios/studio-' . $user->id);
        }

        if($user->role_id === Role::ARTIST){
            Storage::disk('public')->deleteDirectory('artists/artist-' . $user->id);
        }

        Storage::disk('public')->deleteDirectory('users/user-' . $user->id);

        $user->notify(new DeleteAccountNotification());

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();


        return Redirect::to('/');
    }

    public function avatar_upload(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => 'nullable|image|max:1024',
        ]);

        $user = auth()->user();

        if($user->avatar){
            Storage::disk('public')->delete($user->avatar);
            $user->update(['avatar' => null]);
        }

        $scaled_image = Image::read($request->file)->scale(160, 160);

        $path = 'users/user-' . $user->id . '/avatar/' . \Str::uuid()->toString() . '.png';

        Storage::disk('public')->put($path, $scaled_image->toPng());

        $user->update(['avatar' => $path]);

        return back()->with('success', 'Avatar aggiornato');
    }

    public function avatar_delete(): RedirectResponse
    {
        $user = auth()->user();

        Storage::disk('public')->delete($user->avatar);
        
        $user->update(['avatar' => null]);

        return back()->with('success', 'Avatar eliminato');
    }

}
