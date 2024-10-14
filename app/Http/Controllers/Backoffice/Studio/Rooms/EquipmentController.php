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
    public function edit(Request $request, Room $room): Response
    {
        $current_category_id = request('current_category_id', 1);
        $categories = EquipmentCategory::pluck('name', 'id');
        if(!is_int($current_category_id) && !in_array($current_category_id, array_keys($categories->toArray()))) $current_category_id = 1;

        $category = EquipmentCategory::with(['equipments' => function($query) use($room){
            $query->where('room_id', $room->id);
        }])->findOrFail($current_category_id);

        $current_category_id = intval($current_category_id);

        return Inertia::render('Backoffice/Studio/Rooms/Equipment', compact('room', 'category', 'categories', 'current_category_id'));
    }

    public function update(Request $request, Room $room): RedirectResponse
    {
        if(empty($request->equipments)) $request->replace($request->except(['equipments']));

        $request->validate([
            'current_category_id' => ['required', 'integer', 'exists:equipment_categories,id'],
            'equipments' => ['sometimes', 'array'],
            'equipments.*.name' => ['required', 'string', 'max:255'],
        ]);

        $room->equipments()->delete();

        foreach ($request->equipments as $equip) {
            Equipment::create([
                'room_id' => $room->id,
                'equipment_category_id' => $request->current_category_id,
                'name' => $equip['name'],
            ]);
        }

        return back()->with('success', 'Equipaggiamento salvato');
    }
}
