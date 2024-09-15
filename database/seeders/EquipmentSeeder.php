<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room\Equipment;
use App\Models\Room\Room;

class EquipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rooms = Room::all();

        foreach ($rooms as $room) {
            for ($i = 1; $i < 16; $i++) {
                Equipment::create([
                    'room_id' => $room->id,
                    'equipment_category_id' => rand(1, 8),
                    'name' => fake()->words(rand(1,3), true),
                ]);
            }
        }
    }
}
