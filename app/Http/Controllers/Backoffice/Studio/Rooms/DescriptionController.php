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

class DescriptionController extends Controller
{
    public function edit(Room $room): Response
    {
        $room_types = RoomType::pluck('name', 'id');
        $room_statuses = RoomStatus::pluck('name', 'id');

        return Inertia::render('Backoffice/Studio/Rooms/Description', compact('room', 'room_types', 'room_statuses'));
    }

    public function update(Request $request, Room $room): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'room_type_id' => 'required|exists:room_types,id',
            // 'color' => 'required|string|starts_with:#|size:7',
            'min_price' => 'required|int|min:0|max:999',
            'area' => 'required|int|min:1|max:999',
            'max_capacity' => 'required|min:1|max:99',
            'description' => 'required|string|min:100',
        ]);

        $room->update($request->toArray() + ['room_status_id' => 2]);

        return redirect()->back();
    }
}
