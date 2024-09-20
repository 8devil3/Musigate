<?php

namespace App\Http\Controllers\Backoffice\Studio;

use App\Http\Controllers\Controller;
use App\Models\Studio\Availability;
use Carbon\Carbon;
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
                    'start' => $av->start ? Carbon::createFromFormat('H:i:s', $av->start)->format('H:i') : null,
                    'end' => $av->end ? Carbon::createFromFormat('H:i:s', $av->end)->format('H:i') : null,
                    'is_open' => $av->is_open,
                ]
            ];
        });

        return Inertia::render('Backoffice/Studio/Availability/Weekly', compact('availability', 'is_open_24_7'));
    }

    public function update(Request $request): RedirectResponse
    {
        $studio = auth()->user()->studio;

        $studio->update([
            'is_open_24_7' => $request->is_open_24_7,
        ]);

        if($request->is_open_24_7){
            foreach ($studio->availability as $availability) {
                $availability->update([
                    'start' => null,
                    'end' => null,
                    'is_open' => true,
                ]);
            }
        } else {
            foreach ($request->availability as $weekday => $req_av) {
                foreach ($studio->availability as $availability) {
                    if($availability->weekday === $weekday) $availability->update($req_av);
                }
            }
        }

        return back()->with('success', 'Disponibilità aggiornata');
    }
}
