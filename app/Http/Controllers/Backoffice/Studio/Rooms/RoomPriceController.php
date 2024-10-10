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
        $weekday = request('weekday', 1);
        $studio = auth()->user()->studio;
        $price_types = Room::PRICE_TYPES;

        $open_weekdays = $studio->availability()->where('is_open', true)->pluck('weekday');
        $timebands = $studio->timebands;
        $timeband_prices = $room->prices;

        return Inertia::render('Backoffice/Studio/Rooms/Prices', compact('room', 'timeband_prices', 'open_weekdays', 'timebands', 'price_types'));
    }

    public function update(Request $request, Room $room): RedirectResponse
    {
        //TODO: validazioi e scrittura a DB

        return to_route('')->with('success', 'Tariffa aggiornata');
    }

    public function destroy(Room $room): RedirectResponse
    {
        return back();
    }
}
