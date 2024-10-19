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
    public function index(Room $room): Response
    {
        $categories = EquipmentCategory::withCount(['equipments as equip_count' => function($query) use($room){
            $query->where('room_id', $room->id);
        }])->get()->mapWithKeys(function($cat){
            return [
                $cat->id => [
                    'name' => $cat->name,
                    'equip_count' => $cat->equip_count,
                ]
            ];
        });

        return Inertia::render('Backoffice/Studio/Rooms/Equipment/Index', compact( 'room', 'categories'));
    }

    public function edit(Room $room, EquipmentCategory $category): Response
    {
        $category->load(['equipments' => function($query) use($room){
            $query->where('room_id', $room->id);
        }]);

        return Inertia::render('Backoffice/Studio/Rooms/Equipment/Edit', compact('room', 'category'));
    }

    public function update(Request $request, Room $room, EquipmentCategory $category): RedirectResponse
    {
        if(empty($request->equipments)) $request->replace($request->except(['equipments']));

        $request->validate([
            'equipments' => ['sometimes', 'array'],
            'equipments.*.name' => ['required', 'string', 'max:255'],
        ]);

        $room->equipments()->where('equipment_category_id', $category->id)->delete();

        if(!empty($request->equipments)){
            foreach ($request->equipments as $equip) {
                Equipment::create([
                    'room_id' => $room->id,
                    'equipment_category_id' => $category->id,
                    'name' => $equip['name'],
                ]);
            }
        }

        return back()->with('success', 'Equipaggiamento salvato');
    }
}
