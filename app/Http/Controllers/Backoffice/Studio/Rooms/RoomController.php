<?php

namespace App\Http\Controllers\Backoffice\Studio\Rooms;

use App\Http\Controllers\Controller;
use App\Models\Room\Room;
use App\Services\CheckStudioInfo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RoomController extends Controller
{
    public function index(): Response
    {
        $rooms = auth()->user()->studio->rooms->load('photos:id,room_id,path');

        return Inertia::render('Backoffice/Studio/Rooms/Index', compact('rooms'));
    }

    public function create(): Response
    {
        $room = [];

        return Inertia::render('Backoffice/Studio/Rooms/CreateEdit', compact('room'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // 'color' => 'required|string|starts_with:#|size:7',
            // 'is_bookable' => 'boolean',
            'is_published' => 'boolean',
            'min_booking' => 'required|integer|min:1|max:24',
            'area' => 'required|int|min:1|max:999',
            'max_capacity' => 'required|min:1|max:99',
            'description' => 'nullable|string|min:100',
        ]);

        $studio = auth()->user()->studio;

        $room = $studio->rooms()->create($request->toArray());

        CheckStudioInfo::update_studio($studio);

        return to_route('sale.edit', $room->id);
    }

    public function edit(Room $room): Response
    {
        return Inertia::render('Backoffice/Studio/Rooms/CreateEdit', compact('room'));
    }

    public function update(Request $request, Room $room): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|starts_with:#|size:7',
            'is_bookable' => 'boolean',
            'is_published' => 'boolean',
            'min_booking' => 'required|integer|min:1|max:24',
            'area' => 'required|int|min:1|max:999',
            'max_capacity' => 'required|min:1|max:99',
            'description' => 'nullable|string|min:100',
        ]);

        $room->update($request->toArray());

        CheckStudioInfo::update_studio($room->studio);

        return back()->with('success', 'Sala aggiornata');
    }

    public function destroy(Room $room): RedirectResponse
    {
        $room->delete();

        CheckStudioInfo::update_studio($room->studio);

        return back()->with('success', 'Sala eliminata');
    }
}
