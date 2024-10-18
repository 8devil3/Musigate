<?php

namespace App\Http\Controllers\Backoffice\Studio;

use App\Http\Controllers\Controller;
use App\Models\Studio\Availability;
use App\Services\GeneratePeriodsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WeeklyAvailabilityController extends Controller
{
    public function index(): Response
    {
        $weekdays = Availability::WEEKDAYS;
        $availability = auth()->user()->studio->availability()->with('timebands')->get();

        return Inertia::render('Backoffice/Studio/Availability/Index', compact('availability', 'weekdays'));
    }

    public function edit(int $availability_id): Response
    {
        $hours = GeneratePeriodsService::generate();
        $weekdays = Availability::WEEKDAYS;

        $studio = auth()->user()->studio;
        $availability = $studio->availability()->with('timebands')->findOrFail($availability_id);

        $copy_from_weekdays = array_filter($weekdays, function($wd) use($availability){
            return $wd !== $availability->weekday;
        }, ARRAY_FILTER_USE_KEY);

        return Inertia::render('Backoffice/Studio/Availability/Edit', compact('availability', 'hours', 'weekdays', 'copy_from_weekdays'));
    }

    public function update(Request $request, int $availability_id): RedirectResponse
    {
        if(empty($request->timebands)) $request->replace($request->except(['timebands']));

        $hours = GeneratePeriodsService::generate();

        $request->validate([
            'is_open' => ['boolean'],
            'open_start' => ['nullable', 'string', 'required_if:is_open,accepted', 'date_format:H:i,H:i:s', 'in:' . implode(',', $hours)],
            'open_end' => ['nullable', 'string', 'required_if:is_open,accepted', 'after:open_start', 'date_format:H:i,H:i:s', 'in:' . implode(',', $hours)],
            'timebands' => ['sometimes', 'array', 'min:2'],
            'timebands.*.name' => ['required', 'distinct', 'string', 'max:255'],
            'timebands.*.start' => ['required', 'string', 'date_format:H:i,H:i:s'],
            'timebands.*.end' => ['required', 'string', 'after:timebands.*.start', 'date_format:H:i,H:i:s'],
        ]);

        $studio = auth()->user()->studio;
        $availability = $studio->availability()->findOrFail($availability_id);
        $is_open = boolval($request->is_open);

        $availability->update([
            'is_open' => $is_open,
            'open_start' => $is_open ? $request->open_start : null,
            'open_end' => $is_open ? $request->open_end : null,
        ]);

        if(!empty($request->timebands && $is_open)){
            //elimino le fasce non presenti nella request (quelle che l'utente ha eliminato)
            $studio_timebands_ids = $studio->timebands()->where('availability_id', $availability_id)->pluck('id');
            $req_timebands_ids = collect($request->timebands)->pluck('id');
            $deleted_timebands = $studio_timebands_ids->diff($req_timebands_ids);
            $studio->timebands()->whereIn('id', $deleted_timebands)->delete();

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
            $studio->timebands()->where('availability_id', $availability_id)->delete();

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

    public function clone(Request $request, int $availability_id): RedirectResponse
    {
        $request->validate([
            'clone_from_weekday' => ['nullable', 'integer', 'min:1', 'max:7'],
        ]);

        $studio = auth()->user()->studio;
        $current_availability = $studio->availability()->findOrFail($availability_id);

        //duplico il giorno di apertura/chiusura
        $source_model = $studio->availability()->where('weekday', $request->clone_from_weekday)->firstOrFail();
        $current_availability->update([
            'is_open' => $source_model->is_open,
            'open_start' => $source_model->open_start,
            'open_end' => $source_model->open_end,
        ]);

        //duplico le fasce orarie
        $source_timebands = $studio->timebands()->where('weekday', $request->clone_from_weekday)->get();
        $studio->timebands()->where('availability_id', $availability_id)->delete();

        foreach ($source_timebands as $stb) {
            $cloned_tb = $stb->replicate();
            $cloned_tb->availability_id = $availability_id;
            $cloned_tb->weekday = $current_availability->weekday;
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
