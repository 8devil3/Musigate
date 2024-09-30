<?php

namespace App\Http\Controllers\Backoffice\Studio;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CancelPolicySettingController extends Controller
{
    public function edit(): Response
    {
        $cancel_settings = auth()->user()->studio->cancel_settings;

        return Inertia::render('Backoffice/Studio/Bookings/CancelPolicySettings', compact('cancel_settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'has_cancel_policy' => 'required|boolean',
            'no_refund_hours' => 'required|integer|min:4|max:48',
            'partial_refund_hours' => 'required|integer|min:' . $request->no_refund_hours +8,
            'partial_refund_percentage' => 'required|integer|min:10|max:90',
        ]);

        auth()->user()->studio->cancel_settings->update($request->toArray());

        return back()->with('success', 'Impostazioni annullamenti salvate');
    }
}
