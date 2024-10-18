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
            for ($i=0; $i < rand(2, 6); $i++) {
                $path = Storage::disk('public')->putFile('studios/studio-' . $room->studio->id . '/rooms/' . $room->id, FakerImage::image());

                RoomPhoto::create([
                    'room_id' => $room->id,
                    'path' => $path,
                    'filename' => basename($path),
                    'sort_index' => $i +1,
                ],);
            }
        }
    }
}
