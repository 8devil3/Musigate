<?php

namespace App\Http\Controllers\Backoffice\Studio;

use App\Http\Controllers\Controller;
use App\Models\Studio\Timeband;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WeeklyAvailabilityController extends Controller
{
    public function edit(Request $request): Response
    {
        $studio = auth()->user()->studio;
        $weekday = session()->get('weekday', 1);
        $timebands = $studio->timebands;

        $availability = $studio->availability->mapWithKeys(function($av){
            return [
                $av->weekday => [
                    'open_start' => $av->open_start,
                    'open_end' => $av->open_end,
                    'is_open' => $av->is_open,
                ]
            ];
        });

        return Inertia::render('Backoffice/Studio/Availability/Edit', compact('availability', 'timebands', 'weekday'));
    }

    public function update(Request $request): RedirectResponse
    {        
        if(empty($request->timebands)) $request->replace($request->except(['timebands']));

        $request->validate([
            'is_open' => ['boolean'],
            'open_start' => ['nullable', 'string', 'required_if:is_open,accepted', 'date_format:H:i,H:i:s'],
            'open_end' => ['nullable', 'string', 'required_if:is_open,accepted', 'after:open_start', 'date_format:H:i,H:i:s'],
            'timebands' => ['sometimes', 'array', 'min:2'],
            'timebands.*.name' => ['required', 'distinct', 'string', 'max:255'],
            'timebands.*.start' => ['required', 'string', 'date_format:H:i,H:i:s'],
            'timebands.*.end' => ['required', 'string', 'after:timebands.*.start', 'date_format:H:i,H:i:s'],
        ]);

        session()->put('weekday', intval($request->weekday));

        $studio = auth()->user()->studio;

        $studio->availability()->where('weekday', $request->weekday)->firstOrFail()->update([
            'is_open' => $request->is_open,
            'open_start' => $request->is_open ? $request->open_start : null,
            'open_end' => $request->is_open ? $request->open_end : null,
        ]);
        
        if(!empty($request->timebands)){
            //elimino le fasce non presenti nella request (quelle che l'utente ha eliminato)
            $studio_timebands_ids = $studio->timebands()->where('weekday', $request->weekday)->pluck('id');
            $req_timebands_ids = collect($request->timebands)->pluck('id');
            $deleted_timebands = $studio_timebands_ids->diff($req_timebands_ids);
            $studio->timebands()->whereIn('id', $deleted_timebands)->delete();

            //aggiorno o creo le fasce orarie
            foreach ($request->timebands as $timeband) {
                $studio->timebands()->updateOrCreate([
                    'id' => $timeband['id'],
                ], [
                    'weekday' => $request->weekday,
                    'name' => ucfirst($timeband['name']),
                    'start' => $timeband['start'],
                    'end' => $timeband['end'],
                ]);
            }
        } else {
            //elimino tutte le fasce del weekday se l'utente le ha rimosse tutte
            $studio->timebands()->where('weekday', $request->weekday)->delete();
        }

        return back()->with('success', 'Disponibilità aggiornata');
    }
}
