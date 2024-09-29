<?php

namespace App\Http\Controllers\Backoffice\Studio\Rooms;

use App\Http\Controllers\Controller;
use App\Models\Room\Room;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RoomController extends Controller
{
    private $rooms;

    public function __construct()
    {
        $this->rooms = Room::where('studio_id', auth()->user()->studio->id);
    }

    public function index(): Response
    {
        $rooms = $this->rooms->with('photos:id,room_id,path')->get();

        return Inertia::render('Backoffice/Studio/Rooms/Index', compact('rooms'));
    }

    public function create(): Response
    {
        $room = [];

        return Inertia::render('Backoffice/Studio/Rooms/CreateEdit', compact('room'));
    }

    public function store(Request $request): RedirectResponse
    {
        // $rooms_count = auth()->user()->studio->rooms()->count();

        // if($this->subscription !== 'business' && $rooms_count >= 2){
        //     return abort(403);
        // }

        $room = Room::create([
            'studio_id' => auth()->user()->studio->id,
        ] + $request->toArray());

        return to_route('rooms.description.edit', $room->id);
    }

    public function edit(int $room_id): Response
    {
        $room = $this->rooms->findOrFail($room_id);

        return Inertia::render('Backoffice/Studio/Rooms/CreateEdit', compact('room'));
    }

    public function update(Request $request, int $room_id): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|starts_with:#|size:7',
            'is_bookable' => 'boolean',
            'is_visible' => 'boolean',
            'price' => 'nullable|required_if:is_bookable,true|int|min:2|max:999',
            'has_discounted_price' => 'boolean',
            'discounted_price' => 'nullable|int|lt:price|min:1',
            'area' => 'required|int|min:1|max:999',
            'max_capacity' => 'required|min:1|max:99',
            'description' => 'required|string|min:100',
        ]);

        $this->rooms->findOrFail($room_id)->update($request->toArray());

        return back()->with('success', 'Sala aggiornata');
    }

    public function delete(int $room_id): RedirectResponse
    {
        $this->rooms->findOrFail($room_id)->delete();

        return back()->with('success', 'Sala eliminata');
    }
}
