<?php

namespace Database\Seeders;

use App\Models\Room\Room;
use App\Models\Room\RoomType;
use App\Models\Studio\Studio;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $studios = Studio::all();

        foreach ($studios as $studio) {
            for ($i=0; $i < 4; $i++) { 
                Room::create([
                    'studio_id' => $studio->id,
                    'room_type_id' => RoomType::inRandomOrder()->pluck('id')->first(),
                    'room_status_id' => 4,
                    'name' => 'Sala ' . fake()->word(),
                    'color' => fake()->hexColor(),
                    'min_price' => rand(0,80),
                    'area' => rand(10, 50),
                    'description' => fake()->paragraphs(2, true),
                    'max_capacity' => rand(2, 6),
                ]);
            }
        }
    }
}
