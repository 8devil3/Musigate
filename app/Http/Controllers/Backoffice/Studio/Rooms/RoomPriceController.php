<?php

namespace App\Http\Controllers\Backoffice\Studio\Rooms;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Picklists;
use App\Models\Room\Room;
use App\Models\Room\RoomTimebandPrice;
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
            ->mapWithKeys(function($av): array {
                return [$av->weekday => [
                    'label' => Picklists::WEEKDAYS[$av->weekday],
                    'availability_id' => $av->id,
                ]];
            });

        $timebands = $studio->timebands;
        $timeband_prices = $room->timeband_prices;

        return Inertia::render('Backoffice/Studio/Rooms/Prices', compact('room', 'timeband_prices', 'open_weekdays', 'timebands', 'price_types'));
    }

    public function update(Request $request, Room $room): RedirectResponse
    {
        $request->validate([
            'price_type' => ['required', 'string', 'in:' . implode(',', array_keys(Picklists::ROOM_PRICE_TYPES))],
        ]);

        $price_type = $request->price_type;

        if($price_type === 'no_price'){
            $room->update([
                'price_type' => $price_type,
                'hourly_price' => null,
                'has_discounted_hourly_price' => false,
                'discounted_hourly_price' => null,
                'monthly_price' => null,
                'has_discounted_monthly_price' => false,
                'discounted_monthly_price' => null,
                'is_bookable' => false,
            ]);

            $room->timeband_prices()->delete();
        } else if($price_type === 'hourly_price'){
            $request->validate([
                'hourly_price' => ['required', 'integer', 'min:2'],
                'has_discounted_hourly_price' => ['boolean'],
                'discounted_hourly_price' => ['nullable', 'required_if:has_discounted_hourly_price,accepted', 'integer', 'min:1', 'lt:hourly_price'],
            ]);

            $room->update([
                'price_type' => $price_type,
                'hourly_price' => $request->hourly_price,
                'has_discounted_hourly_price' => boolval($request->has_discounted_hourly_price),
                'discounted_hourly_price' => boolval($request->has_discounted_hourly_price) ? $request->discounted_hourly_price : null,

                'monthly_price' => null,
                'has_discounted_monthly_price' => false,
                'discounted_monthly_price' => null,
            ]);

            $room->timeband_prices()->delete();
        } else if($price_type === 'monthly_price'){
            $request->validate([
                'monthly_price' => ['required', 'integer', 'min:2'],
                'has_discounted_monthly_price' => ['boolean'],
                'discounted_monthly_price' => ['nullable', 'required_if:has_discounted_monthly_price,accepted', 'integer', 'min:1', 'lt:monthly_price'],
            ]);

            $room->update([
                'price_type' => $price_type,
                'monthly_price' => $request->monthly_price,
                'has_discounted_monthly_price' => boolval($request->has_discounted_monthly_price),
                'discounted_monthly_price' => boolval($request->has_discounted_monthly_price) ? $request->discounted_monthly_price : null,

                'hourly_price' => null,
                'has_discounted_hourly_price' => false,
                'discounted_hourly_price' => null,
            ]);

            $room->timeband_prices()->delete();
        } else if($price_type === 'timebands_price'){
            $request->validate([
                'timeband_prices' => ['nullable', 'required_if:price_type,timebands_price', 'array'],
                'timeband_prices.*.timeband_id' => ['required', 'integer', 'exists:timebands,id'],
                'timeband_prices.*.price' => ['required', 'integer', 'min:2'],
                'timeband_prices.*.has_discounted_price' => ['boolean'],
                'timeband_prices.*.discounted_price' => ['nullable', 'required_if:timeband_prices.*.has_discounted_price,accepted', 'integer', 'min:1', 'lt:timeband_prices.*.price'],
            ]);

            $room->update([
                'price_type' => $price_type,
                'hourly_price' => null,
                'has_discounted_hourly_price' => false,
                'discounted_hourly_price' => null,
                'monthly_price' => null,
                'has_discounted_monthly_price' => false,
                'discounted_monthly_price' => null,
            ]);

            foreach ($request->timeband_prices as $tbp) {
                $room->timeband_prices()->updateOrCreate([
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
