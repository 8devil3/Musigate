<?php

namespace App\Http\Controllers\Backoffice\Studio;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Picklists;
use App\Models\Studio\Availability;
use App\Services\GeneratePeriodsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AvailabilityController extends Controller
{
    public function index(): Response
    {
        $weekdays = Picklists::WEEKDAYS;
        $availability = auth()->user()->studio->availability()->with('timebands')->get();

        return Inertia::render('Backoffice/Studio/Availability/Index', compact('availability', 'weekdays'));
    }

    public function edit(Availability $availability): Response
    {
        if($availability->studio->user->id !== auth()->id()) abort(403);

        $hours = GeneratePeriodsService::generate();
        $weekdays = Picklists::WEEKDAYS;
        $open_types = Picklists::OPEN_TYPES;

        $studio = auth()->user()->studio;
        $availability->load('timebands');

        $copy_from_weekdays = array_filter($weekdays, function($wd) use($availability){
            return $wd !== $availability->weekday;
        }, ARRAY_FILTER_USE_KEY);

        return Inertia::render('Backoffice/Studio/Availability/Edit', compact('availability', 'hours', 'weekdays', 'open_types', 'copy_from_weekdays'));
    }

    public function update(Request $request, Availability $availability): RedirectResponse
    {
        if($availability->studio->user->id !== auth()->id()) abort(403);

        if(empty($request->timebands) || $request->open_type === 'close') $request->replace($request->except(['timebands']));
        else if(!empty($request->timebands) && $request->timebands[count($request->timebands) -1]['end'] !== $request->open_end) return back()->withErrors('L\'orario di fine dell\'ultima fascia oraria deve coincidere con quella di chiusura dello Studio.');

        $request->validate([
            'open_type' => ['required', 'string', 'in:' . implode(',', array_keys(Picklists::OPEN_TYPES))],
            'timebands' => ['sometimes', 'array', 'min:2'],
            'timebands.*.name' => ['required', 'distinct', 'string', 'max:255'],
        ]);

        $hours = implode(',',GeneratePeriodsService::generate());

        //gestisco gli orari dello studio
        if($request->open_type === 'open'){
            $request->validate([
                'open_start' => ['required', 'string', 'size:5', 'date_format:H:i,H:i:s', 'in:' . $hours],
                'open_end' => ['required', 'string', 'size:5', 'after:open_start', 'date_format:H:i,H:i:s', 'in:' . $hours],
                'timebands.*.start' => ['required', 'string', 'size:5', 'date_format:H:i,H:i:s'],
                'timebands.*.end' => ['required', 'string', 'size:5', 'after:timebands.*.start', 'date_format:H:i,H:i:s'],
            ]);

            $availability->update([
                'open_type' => $request->open_type,
                'open_start' => $request->open_start,
                'open_end' => $request->open_end,
            ]);
        } else if($request->open_type === 'open_overnight'){
            $request->validate([
                'open_start' => ['required', 'string', 'size:5', 'date_format:H:i,H:i:s', 'in:' . $hours],
                'open_end' => ['required', 'string', 'size:5', 'date_format:H:i,H:i:s', 'in:' . $hours],
                'timebands.*.start' => ['required', 'string', 'size:5', 'date_format:H:i,H:i:s'],
                'timebands.*.end' => ['required', 'string', 'size:5', 'date_format:H:i,H:i:s'],
            ]);

            $availability->update([
                'open_type' => $request->open_type,
                'open_start' => $request->open_start,
                'open_end' => $request->open_end,
            ]);
        } else if($request->open_type === 'open_h24'){
            $request->validate([
                'open_start' => ['required', 'string', 'size:5', 'date_format:H:i,H:i:s', 'in:' . $hours],
                'open_end' => ['required', 'string', 'size:5', 'after:open_start', 'date_format:H:i,H:i:s', 'in:' . $hours],
                'timebands.*.start' => ['required', 'string', 'size:5', 'date_format:H:i,H:i:s'],
                'timebands.*.end' => ['required', 'string', 'size:5', 'after:timebands.*.start', 'date_format:H:i,H:i:s'],
            ]);

            $availability->update([
                'open_type' => $request->open_type,
                'open_start' => '00:00',
                'open_end' => '24:00',
            ]);
        } else if($request->open_type === 'close'){
            $availability->update([
                'open_type' => $request->open_type,
                'open_start' => null,
                'open_end' => null,
            ]);
        }

        $studio = auth()->user()->studio;

        //gestisco le fasce orarie
        if($request->open_type !== 'close' && !empty($request->timebands)){
            //elimino le fasce non presenti nella request (quelle che l'utente ha eliminato)
            $req_timebands_ids = collect($request->timebands)->pluck('id')->toArray();
            $studio->timebands()->where('weekday', $availability->weekday)->whereNotIn('id', $req_timebands_ids)->delete();

            //aggiorno o creo le fasce orarie
            foreach ($request->timebands as $timeband) {
                $availability->timebands()->updateOrCreate([
                    'id' => $timeband['id'],
                ], [
                    'studio_id' => $studio->id,
                    'weekday' => $availability->weekday,
                    'name' => $timeband['name'],
                    'start' => $timeband['start'],
                    'end' => $timeband['end'],
                ]);
            }
        } else {
            //elimino tutte le fasce del availability_id se l'utente le ha rimosse tutte
            $availability->timebands()->delete();

            //aggiorno le tariffe su "nessuna tariffa" ed elimino le eventuali tariffe con fasce orarie
            $rooms = $studio->rooms;
            if(!empty($rooms)){
                foreach($rooms as $room){
                    $room->update([
                        'price_type' => 'no_price',
                        'hourly_price' => null,
                        'has_dicounted_hourly_price' => false,
                        'dicounted_hourly_price' => null,
                        'monthly_price' => null,
                        'has_dicounted_monthly_price' => false,
                        'dicounted_monthly_price' => null,
                    ]);

                    $room->timeband_prices()->delete();
                }
            }
        }

        return back()->with('success', 'Disponibilità aggiornata');
    }

    public function clone(Request $request, Availability $availability): RedirectResponse
    {
        if($availability->studio->user->id !== auth()->id()) abort(403);

        $request->validate([
            'clone_from_weekday' => ['nullable', 'integer', 'min:1', 'max:7'],
        ]);

        $studio = auth()->user()->studio;
        $current_availability = $availability;

        //duplico il giorno di apertura/chiusura
        $source_model = $studio->availability()->where('weekday', $request->clone_from_weekday)->firstOrFail();
        $current_availability->update([
            'open_type' => $source_model->open_type,
            'open_start' => $source_model->open_start,
            'open_end' => $source_model->open_end,
        ]);

        //duplico le fasce orarie
        $source_timebands = $studio->timebands()->where('weekday', $request->clone_from_weekday)->get();
        $availability->timebands()->delete();

        foreach ($source_timebands as $stb) {
            $cloned_timeband = $stb->replicate();
            $cloned_timeband->availability_id = $availability->id;
            $cloned_timeband->weekday = $current_availability->weekday;
            $cloned_timeband->created_at = now();
            $cloned_timeband->save();
        }

        //aggiorno le tariffe su "nessuna tariffa" ed elimino le eventuali tariffe con fasce orarie
        $rooms = $studio->rooms;
        if(!empty($rooms)){
            foreach($rooms as $room){
                $room->update([
                    'price_type' => 'no_price',
                    'fixed_price' => null,
                    'has_dicounted_fixed_price' => false,
                    'dicounted_fixed_price' => null,
                ]);

                $room->timeband_prices()->delete();
            }
        }

        return back()->with('success', 'Disponibilità copiata');
    }
}
