<?php

namespace Database\Seeders;

use App\Models\Room\Room;
use App\Models\Room\RoomPhoto;
use App\Services\FakerImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class RoomPhotoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rooms = Room::all();

        foreach ($rooms as $room) {
            for ($rf=0; $rf < rand(2, 6); $rf++) { 
                RoomPhoto::create([
                    'room_id' => $room->id,
                    'path' => Storage::disk('public')->putFile('users/user-' . $room->studio->user_id . '/studio/rooms/' . $room->id, FakerImage::image())
                ],);
            }
        }
    }
}
