<?php

namespace App\Http\Controllers\Backoffice\Studio\Rooms;

use App\Http\Controllers\Controller;
use App\Models\Room\Room;
use App\Models\Room\RoomType;
use App\Models\Status\RoomStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RoomController extends Controller
{
    public function index(): Response
    {
        $room_types = RoomType::pluck('name', 'id');
        $room_statuses = RoomStatus::pluck('name', 'id');
        $rooms = auth()->user()->studio->rooms()->get();

        return Inertia::render('Backoffice/Studio/Rooms/Index', compact('rooms', 'room_types', 'room_statuses'));
    }

    public function store(): RedirectResponse
    {
        // $rooms_count = auth()->user()->studio->rooms()->count();

        // if($this->subscription !== 'business' && $rooms_count >= 2){
        //     return abort(403);
        // }

        $room = auth()->user()->studio->rooms()->create();

        return to_route('rooms.description.edit', $room->id);
    }

    public function delete(Room $room): RedirectResponse
    {
        $room->delete();

        return redirect()->back();
    }

    // Aggiorna lo status della Sala
    public function update_status(Request $request, Room $room): RedirectResponse
    {
        if ($room->room_status_id === 3) {
            abort(403);

            //Notification:: invio email di notifica al superadmin per sollecitarlo della moderazione della Sala
        }

        $room->update($request->toArray());

        //la pubblicazione è delegata al superadmin, previa moderazione della Sala
        
        return redirect()->back();
    }
}
