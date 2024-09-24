<?php

namespace App\Http\Controllers\Backoffice\Studio;

use App\Http\Controllers\Controller;
use App\Models\Studio\CancelPolicySetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CancelPolicySettingController extends Controller
{
    public function edit(): Response
    {
        $cancel_settings = auth()->user()->studio->cancel_settings;

        return Inertia::render('Backoffice/Studio/Settings/CancelPolicySettings', compact('cancel_settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        auth()->user()->studio->cancel_settings->update($request->toArray());

        return back()->with('success', 'Impostazioni salvate');
    }
}
