<?php

namespace App\Http\Controllers\Backoffice\Studio;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WeeklyAvailabilityController extends Controller
{
    public function edit(): Response
    {
        $studio = auth()->user()->studio;
        $is_open_24_7 = $studio->is_open_24_7;

        $availability = $studio->availability->mapWithKeys(function($av){
            return [
                $av->weekday => [
                    'open_start' => $av->open_start,
                    'open_end' => $av->open_end,
                    'is_open' => $av->is_open,
                    'timebands' => $av->timebands ?? [],
                ]
            ];
        });

        return Inertia::render('Backoffice/Studio/Availability/Edit', compact('availability', 'is_open_24_7'));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'is_open' => ['boolean'],
            'open_start' => ['nullable', 'string', 'required_if:is_open,accepted', 'date_format:H:i,H:i:s'],
            'open_end' => ['nullable', 'string', 'required_if:is_open,accepted', 'after:open_start', 'date_format:H:i,H:i:s'],
            'timebands' => ['array', 'min:2'],
            'timebands.*.name' => ['required', 'string', 'max:255'],
            'timebands.*.start' => ['required', 'string', 'date_format:H:i,H:i:s'],
            'timebands.*.end' => ['required', 'string', 'after:timebands.*.start', 'date_format:H:i,H:i:s'],
        ]);

        auth()->user()->studio->availability()->where('weekday', $request->weekday)->firstOrFail()->update([
            'is_open' => $request->is_open,
            'open_start' => $request->is_open ? $request->open_start : null,
            'open_end' => $request->is_open ? $request->open_end : null,
            'timebands' => $request->timebands,
        ]);

        return back()->with('success', 'Disponibilità aggiornata');
    }
}
