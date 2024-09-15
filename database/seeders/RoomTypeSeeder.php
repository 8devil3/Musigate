<?php

namespace Database\Seeders;

use App\Models\Room\RoomType;
use Illuminate\Database\Seeder;

class RoomTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $room_types = [
            'Sala prove',
            'Control room',
            'Recording room',
            'ISO booth',
            'Writing room'
        ];

        foreach ($room_types as $room_type) {
            RoomType::create(['name' => $room_type]);
        }
    }
}
