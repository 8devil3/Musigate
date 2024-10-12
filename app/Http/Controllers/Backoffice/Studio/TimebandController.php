<?php

namespace App\Http\Controllers\Backoffice\Studio;

use App\Http\Controllers\Controller;
use App\Models\Studio\Availability;
use Carbon\CarbonPeriod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TimebandController extends Controller
{
    public function edit(Request $request): Response
    {
        $weekday = request('weekday', 1);
        if(!is_int($weekday) && ($weekday < 1 || $weekday > 7)) $weekday = 1;

        $weekdays = Availability::WEEKDAYS;
        $arr_hours = CarbonPeriod::create('00:00', '30 minutes', '24:00');
        $hours = [];

        foreach ($arr_hours as $key => $hour) {
            if($key === count($arr_hours) -1){
                $hours[] = '24:00';
            } else {
                $hours[] = $hour->format('H:i');
            }
        }

        $studio = auth()->user()->studio;
        $availability = $studio->availability()->where('weekday', $weekday)->firstOrFail();
        $timebands = $studio->timebands()->where('weekday', $weekday)->get();
        $all_timebands = $studio->timebands()->pluck('weekday')->mapWithKeys(function($wd) use($weekdays){
            return [$wd => $weekdays[$wd]];
        });

        return Inertia::render('Backoffice/Studio/Timebands', compact('timebands', 'all_timebands', 'availability', 'hours', 'weekday', 'weekdays'));
    }

    public function update(Request $request): RedirectResponse
    {
        if(empty($request->timebands)) $request->replace($request->except(['timebands']));

        $request->validate([
            'weekday' => ['required', 'integer', 'min:1', 'max:7'],
            'clone_from_weekday' => ['nullable', 'integer', 'min:1', 'max:7'],
            'timebands' => ['sometimes', 'array', 'min:2'],
            'timebands.*.name' => ['required', 'distinct', 'string', 'max:255'],
            'timebands.*.start' => ['required', 'string', 'date_format:H:i,H:i:s'],
            'timebands.*.end' => ['required', 'string', 'after:timebands.*.start', 'date_format:H:i,H:i:s'],
        ]);

        $studio = auth()->user()->studio;

        if(!empty($request->clone_from_weekday)){
            $timebands = $studio->timebands()->where('weekday', $request->clone_from_weekday)->get();
            foreach ($timebands as $tb) {
                $cloned_tb = $tb->replicate();
                $cloned_tb->weekday = $request->weekday;
                $cloned_tb->created_at = now();
                $cloned_tb->save();
            }
        } else {
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
                        'name' => $timeband['name'],
                        'start' => $timeband['start'],
                        'end' => $timeband['end'],
                    ]);
                }
            } else {
                //elimino tutte le fasce del weekday se l'utente le ha rimosse tutte
                $studio->timebands()->where('weekday', $request->weekday)->delete();
            }
        }

        return back()->with('success',' Fasce orarie salvate');
    }
}
