<?php

namespace App\Http\Controllers\Backoffice\Studio\Rooms;

use App\Http\Controllers\Controller;
use App\Models\Room\Room;
use App\Models\Room\RoomPrice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RoomPriceController extends Controller
{
    public function edit(Request $request, Room $room): Response
    {
        $studio = auth()->user()->studio;
        $price_types = Room::PRICE_TYPES;

        $open_weekdays = $studio->availability()->where('is_open', true)->pluck('weekday');
        $timebands = $studio->timebands;
        $timeband_prices = $room->prices;

        return Inertia::render('Backoffice/Studio/Rooms/Prices', compact('room', 'timeband_prices', 'open_weekdays', 'timebands', 'price_types'));
    }

    public function update(Request $request, Room $room): RedirectResponse
    {
        $request->validate([
            'price_type' => ['required', 'string', 'in:' . implode(',', array_keys(Room::PRICE_TYPES))],
        ]);

        $price_type = $request->price_type;

        if($price_type === 'no_price'){
            $room->update([
                'price_type' => $price_type,
                'fixed_price' => null,
                'has_discounted_fixed_price' => false,
                'discounted_fixed_price' => null,
                'is_bookable' => false,
            ]);

            $room->prices()->delete();
        } else if($price_type === 'fixed_price'){
            $request->validate([
                'fixed_price' => ['required', 'integer', 'min:2'],
                'has_discounted_fixed_price' => ['boolean'],
                'discounted_fixed_price' => ['nullable', 'required_if:has_discounted_fixed_price,accepted', 'integer', 'min:1', 'lt:fixed_price'],
            ]);

            $room->update([
                'price_type' => $price_type,
                'fixed_price' => $request->fixed_price,
                'has_discounted_fixed_price' => boolval($request->has_discounted_fixed_price),
                'discounted_fixed_price' => boolval($request->has_discounted_fixed_price) ? $request->discounted_fixed_price : null,
            ]);

            $room->prices()->delete();
        } else if($price_type === 'timebands_price'){
            $room->update([
                'price_type' => $price_type,
                'fixed_price' => null,
                'has_discounted_fixed_price' => false,
                'discounted_fixed_price' => null,
            ]);

            $request->validate([
                'timeband_prices' => ['nullable', 'required_if:price_type,timebands_price', 'array'],
                'timeband_prices.*.timeband_id' => ['required', 'integer', 'exists:timebands,id'],
                'timeband_prices.*.price' => ['required', 'integer', 'min:2'],
                'timeband_prices.*.has_discounted_price' => ['boolean'],
                'timeband_prices.*.discounted_price' => ['nullable', 'required_if:timeband_prices.*.has_discounted_price,accepted', 'integer', 'min:1', 'lt:timeband_prices.*.price'],
            ]);

            foreach ($request->timeband_prices as $tbp) {
                $room->prices()->updateOrCreate([
                    'id' => $tbp['id'],
                ], [
                    'timeband_id' => $tbp['timeband_id'],
                    'price' => $tbp['price'],
                    'has_discounted_price' => boolval($tbp['has_discounted_price']),
                    'discounted_price' => boolval($tbp['has_discounted_price']) ? $tbp['discounted_price'] : null,
                ]);
            }
        }

        return back()->with('success', 'Tariffe aggiornate');
    }
}