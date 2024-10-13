<?php

namespace App\Http\Controllers\Backoffice\Studio;

use App\Http\Controllers\Controller;
use App\Models\Room\RoomPrice;
use App\Models\Studio\Availability;
use App\Services\GeneratePeriodsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WeeklyAvailabilityController extends Controller
{
    public function edit(Request $request): Response
    {
        $current_weekday = request('current_weekday', 1);
        if(!is_int($current_weekday) && ($current_weekday < 1 || $current_weekday > 7)) $current_weekday = 1;

        $weekdays = Availability::WEEKDAYS;
        $hours = GeneratePeriodsService::generate();

        $studio = auth()->user()->studio;
        $availability = $studio->availability()->where('weekday', $current_weekday)->firstOrFail();
        $timebands = $studio->timebands()->where('weekday', $current_weekday)->get();
        $all_timebands = $studio->timebands()->whereNot('weekday', $current_weekday)->pluck('weekday')->mapWithKeys(function($wd) use($weekdays){
            return [$wd => $weekdays[$wd]];
        });

        return Inertia::render('Backoffice/Studio/Availability', compact('availability', 'timebands', 'all_timebands', 'current_weekday', 'hours', 'weekdays'));
    }

    public function update(Request $request): RedirectResponse
    {
        if(empty($request->timebands)) $request->replace($request->except(['timebands']));

        $hours = GeneratePeriodsService::generate();

        $request->validate([
            'current_weekday' => ['required', 'integer', 'min:1', 'max:7'],
            'is_open' => ['boolean'],
            'open_start' => ['nullable', 'string', 'required_if:is_open,accepted', 'date_format:H:i,H:i:s', 'in:' . implode(',', $hours)],
            'open_end' => ['nullable', 'string', 'required_if:is_open,accepted', 'after:open_start', 'date_format:H:i,H:i:s', 'in:' . implode(',', $hours)],
            'timebands' => ['sometimes', 'array', 'min:2'],
            'timebands.*.name' => ['required', 'distinct', 'string', 'max:255'],
            'timebands.*.start' => ['required', 'string', 'date_format:H:i,H:i:s'],
            'timebands.*.end' => ['required', 'string', 'after:timebands.*.start', 'date_format:H:i,H:i:s'],
        ]);

        $studio = auth()->user()->studio;
        $availability = $studio->availability;
        $is_open = boolval($request->is_open);

        $availability->where('weekday', $request->current_weekday)->firstOrFail()->update([
            'weekday' => $request->current_weekday,
            'is_open' => $is_open,
            'open_start' => $is_open ? $request->open_start : null,
            'open_end' => $is_open ? $request->open_end : null,
        ]);

        if(!empty($request->timebands && $is_open)){
            //elimino le fasce non presenti nella request (quelle che l'utente ha eliminato)
            $studio_timebands_ids = $studio->timebands()->where('weekday', $request->current_weekday)->pluck('id');
            $req_timebands_ids = collect($request->timebands)->pluck('id');
            $deleted_timebands = $studio_timebands_ids->diff($req_timebands_ids);
            $studio->timebands()->whereIn('id', $deleted_timebands)->delete();

            //aggiorno o creo le fasce orarie
            foreach ($request->timebands as $timeband) {
                $studio->timebands()->updateOrCreate([
                    'id' => $timeband['id'],
                ], [
                    'weekday' => $request->current_weekday,
                    'name' => $timeband['name'],
                    'start' => $timeband['start'],
                    'end' => $timeband['end'],
                ]);
            }
        } else {
            //elimino tutte le fasce del weekday se l'utente le ha rimosse tutte
            $studio->timebands()->where('weekday', $request->current_weekday)->delete();

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

                    $room->prices()->delete();
                }
            }
        }

        return back()->with('success', 'Disponibilità aggiornata');
    }

    public function clone(Request $request): RedirectResponse
    {
        $request->validate([
            'current_weekday' => ['required', 'integer', 'min:1', 'max:7'],
            'clone_from_weekday' => ['nullable', 'integer', 'min:1', 'max:7'],
        ]);

        $studio = auth()->user()->studio;
        $availability = $studio->availability;

        //copio il giorno di apertura/chiusura
        $source_model = $availability->where('weekday', $request->clone_from_weekday)->firstOrFail();
        $availability->where('weekday', $request->current_weekday)->firstOrFail()->update([
            'weekday' => $request->current_weekday,
            'is_open' => $source_model->is_open,
            'open_start' => $source_model->open_start,
            'open_end' => $source_model->open_end,
        ]);

        //copio le fasce orarie
        $source_timebands = $studio->timebands()->where('weekday', $request->clone_from_weekday)->get();
        $studio->timebands()->where('weekday', $request->current_weekday)->delete();
        
        foreach ($source_timebands as $stb) {
            $cloned_tb = $stb->replicate();
            $cloned_tb->weekday = $request->current_weekday;
            $cloned_tb->created_at = now();
            $cloned_tb->save();
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

                $room->prices()->delete();
            }
        }

        return back()->with('success', 'Disponibilità copiata');
    }
}
