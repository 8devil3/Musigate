<?php

namespace App\Http\Controllers\Backoffice\Studio\Rooms;

use App\Http\Controllers\Controller;
use App\Models\Room\Equipment;
use App\Models\Room\EquipmentCategory;
use App\Models\Room\Room;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EquipmentController extends Controller
{
    public function edit(Room $room): Response
    {
        $equipment = Equipment::where('room_id', $room->id)->get(['id', 'name', 'equipment_category_id']);
        $equipment_categories = EquipmentCategory::pluck('name', 'id');

        return Inertia::render('Backoffice/Studio/Rooms/Equipment', compact('room', 'equipment', 'equipment_categories'));
    }

    public function update(Request $request, Room $room): RedirectResponse
    {
        $request->validate([
            'equipment.*.equipment_category_id' => 'required|int|exists:equipment_categories,id',
            'equipment.*.name' => 'required|string|max:255',
        ]);

        Equipment::where('room_id', $room->id)->delete();
        
        foreach ($request->equipment as $equip) {
            Equipment::create([
                'room_id' => $room->id,
                'equipment_category_id' => $equip['equipment_category_id'],
                'name' => $equip['name'],
            ]);
        }

        return back()->with('success', 'Equipaggiamento salvato');
    }
}
