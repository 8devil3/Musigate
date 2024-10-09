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
    public function edit(Room $room): Response
    {
        $allow_fractional_durations = $room->studio->booking_settings->allow_fractional_durations;

        $prices = $room->prices()->get()->mapWithKeys(function($price){
            return [
                $price->weekday => [
                    'start' => $price->start,
                    'end' => $price->end,
                    'price' => $price->price,
                ]
            ];
        });

        return Inertia::render('Backoffice/Studio/Rooms/Prices', compact('room', 'prices', 'allow_fractional_durations'));
    }

    public function update(Request $request, Room $room): RedirectResponse
    {
        return to_route('')->with('success', 'Tariffa aggiornata');
    }

    public function destroy(Room $room): RedirectResponse
    {
        return back();
    }
}
