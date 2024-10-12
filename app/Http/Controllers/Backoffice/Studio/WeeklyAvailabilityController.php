<?php

namespace App\Http\Controllers\Backoffice\Studio;

use App\Http\Controllers\Controller;
use App\Models\Studio\Availability;
use App\Models\Studio\Timeband;
use Carbon\CarbonPeriod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WeeklyAvailabilityController extends Controller
{
    public function edit(): Response
    {
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

        $availability = auth()->user()->studio->availability;

        return Inertia::render('Backoffice/Studio/Availability/Edit', compact('availability', 'hours', 'weekdays'));
    }

    public function update(Request $request): RedirectResponse
    {
        $arr_hours = CarbonPeriod::create('00:00', '30 minutes', '24:00');
        $hours = [];
        foreach ($arr_hours as $key => $hour) {
            if($key === count($arr_hours) -1){
                $hours[] = '24:00';
            } else {
                $hours[] = $hour->format('H:i');
            }
        }

        $request->validate([
            'availability.*.weekday' => ['required', 'integer', 'min:1', 'max:7'],
            'availability.*.is_open' => ['boolean'],
            'availability.*.open_start' => ['nullable', 'string', 'required_if:availability.*.is_open,accepted', 'date_format:H:i,H:i:s', 'in:' . implode(',', $hours)],
            'availability.*.open_end' => ['nullable', 'string', 'required_if:availability.*.is_open,accepted', 'after:availability.*.open_start', 'date_format:H:i,H:i:s', 'in:' . implode(',', $hours)],
        ]);

        $availability = auth()->user()->studio->availability;

        foreach ($request->availability as $av) {
            $is_open = boolval($av['is_open']);

            $availability->where('weekday', $av['weekday'])->firstOrFail()->update([
                'weekday' => $av['weekday'],
                'is_open' => $is_open,
                'open_start' => $is_open ? $av['open_start'] : null,
                'open_end' => $is_open ? $av['open_end'] : null,
            ]);
        }

        return back()->with('success', 'Disponibilità aggiornata');
    }
}
