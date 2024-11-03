<?php

namespace App\Http\Controllers\Backoffice\Studio\Rooms;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Picklists;
use App\Models\Room\Room;
use App\Models\Studio\Availability;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RoomPriceController extends Controller
{
    public function edit(Room $room): Response
    {
        $price_types = Picklists::ROOM_PRICE_TYPES;
        $studio = auth()->user()->studio;

        $open_weekdays = $studio->availability()->whereNot('open_type', 'close')->get()
            ->map(function($av): array {
                return [
                    'weekday' => $av->weekday,
                    'label' => Picklists::WEEKDAYS[$av->weekday],
                    'availability_id' => $av->id,
                ];
            });

        $timebands = $studio->timebands;
        $timeband_prices = $room->timeband_prices;
        $hourly_prices = $room->hourly_prices;

        //riempio l'array delle tariffe orarie con valori nulli per i giorni (open_weekdays) senza tariffe
        foreach ($open_weekdays as $owd) {
            if(!in_array($owd['availability_id'], $room->hourly_prices()->pluck('availability_id')->toArray())){
                $hourly_prices[] = [
                    'id' => null,
                    'availability_id' => $owd['availability_id'],
                    'price' => null,
                    'has_discounted_price' => false,
                    'discounted_price' => null,
                ];
            }
        }

        //se non ci sono tariffe a fasce orarie creo un array con valori nulli
        if($timeband_prices->isEmpty()){
            foreach ($timebands as $tb) {
                $timeband_prices[] = [
                    'id' => null,
                    'timeband_id' => $tb['id'],
                    'price' => null,
                    'has_discounted_price' => false,
                    'discounted_price' => null,
                ];
            }
        }

        return Inertia::render('Backoffice/Studio/Rooms/Prices', compact('room', 'timeband_prices', 'hourly_prices', 'open_weekdays', 'timebands', 'price_types'));
    }

    public function update(Request $request, Room $room): RedirectResponse
    {
        $request->validate([
            'price_type' => ['required', 'string', 'in:' . implode(',', array_keys(Picklists::ROOM_PRICE_TYPES))],
        ]);

        $price_type = $request->price_type;

        switch ($price_type) {
            case 'no_price':
                $room->update([
                    'price_type' => $price_type,
                    'monthly_price' => null,
                    'has_discounted_monthly_price' => false,
                    'discounted_monthly_price' => null,
                    'is_bookable' => false,
                ]);

                $room->timeband_prices()->delete();
                $room->hourly_prices()->delete();
            break;

            case 'hourly_price':
                $request->validate([
                    'hourly_prices' => ['required', 'array', 'min:1', 'max:7'],
                    'hourly_prices.*.availability_id' => ['required', 'integer', 'exists:availabilities,id'],
                    'hourly_prices.*.price' => ['required', 'integer', 'min:2'],
                    'hourly_prices.*.has_discounted_price' => ['boolean'],
                    'hourly_prices.*.discounted_price' => ['nullable', 'required_if:has_discounted_price,accepted', 'integer', 'min:1', 'lt:hourly_prices.*.price'],
                ]);

                $room->update([
                    'price_type' => $price_type,
                    'fixed_price' => null,
                    'has_discounted_fixed_price' => false,
                    'discounted_fixed_price' => null,
                ]);

                $room->timeband_prices()->delete();

                foreach ($request->hourly_prices as $hp) {
                    $has_discounted_price = boolval($hp['has_discounted_price']);
    
                    $room->hourly_prices()->updateOrCreate([
                        'id' => $hp['id'],
                    ],[
                        'availability_id' => $hp['availability_id'],
                        'weekday' => Availability::findOrFail($hp['availability_id'])->weekday,
                        'price' => $hp['price'],
                        'has_discounted_price' => $has_discounted_price,
                        'discounted_price' => $has_discounted_price ? $hp['discounted_price'] : null,
                    ]);
                }
            break;

            case 'monthly_price':
                $request->validate([
                    'monthly_price' => ['required', 'integer', 'min:2'],
                    'has_discounted_monthly_price' => ['boolean'],
                    'discounted_monthly_price' => ['nullable', 'required_if:has_discounted_monthly_price,accepted', 'integer', 'min:1', 'lt:monthly_price'],
                ]);

                $has_discounted_monthly_price = boolval($request->has_discounted_monthly_price);

                $room->update([
                    'price_type' => $price_type,
                    'monthly_price' => $request->monthly_price,
                    'has_discounted_monthly_price' => $has_discounted_monthly_price,
                    'discounted_monthly_price' => $has_discounted_monthly_price ? $request->discounted_monthly_price : null,
                ]);

                $room->timeband_prices()->delete();
                $room->hourly_prices()->delete();
            break;

            case 'timebands_price':
                $request->validate([
                    'timeband_prices' => ['nullable', 'required_if:price_type,timebands_price', 'array'],
                    'timeband_prices.*.timeband_id' => ['required', 'integer', 'exists:timebands,id'],
                    'timeband_prices.*.price' => ['required', 'integer', 'min:2'],
                    'timeband_prices.*.has_discounted_price' => ['boolean'],
                    'timeband_prices.*.discounted_price' => ['nullable', 'required_if:timeband_prices.*.has_discounted_price,accepted', 'integer', 'min:1', 'lt:timeband_prices.*.price'],
                ]);

                $room->update([
                    'price_type' => $price_type,
                    'monthly_price' => null,
                    'has_discounted_monthly_price' => false,
                    'discounted_monthly_price' => null,
                ]);

                $room->hourly_prices()->delete();

                foreach ($request->timeband_prices as $tbp) {
                    $has_discounted_price = boolval($tbp['has_discounted_price']);

                    $room->timeband_prices()->updateOrCreate([
                        'id' => $tbp['id'],
                    ], [
                        'timeband_id' => $tbp['timeband_id'],
                        'price' => $tbp['price'],
                        'has_discounted_price' => $has_discounted_price,
                        'discounted_price' => $has_discounted_price ? $tbp['discounted_price'] : null,
                    ]);
                }
            break;
        }

        return back()->with('success', 'Tariffe aggiornate');
    }
}
