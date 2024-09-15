<?php

namespace App\Http\Controllers\Backoffice\Studio\Rooms;

use App\Http\Controllers\Controller;

use App\Models\Room\Room;
use App\Models\Room\RoomPhoto;
use App\Http\Requests\PhotoRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class RoomPhotoController extends Controller
{
    public function edit(Room $room): Response
    {
        $photos = $room->photos->toArray();

        return Inertia::render('Backoffice/Studio/Rooms/Photos', compact('photos', 'room'));
    }

    public function update(PhotoRequest $request, Room $room): RedirectResponse
    {
        $request->validated();

        foreach($request->photos as $photo){
            $path = Storage::disk('public')->putFile('users/user-' . auth()->id() . '/studio/rooms/' . $room->id, $photo);

            RoomPhoto::create([
                'room_id' => $room->id,
                'path' => $path
            ]);
        }

        $room->update(['room_status_id' => 2]);

        return redirect()->back();
    }

    public function featured(Room $room, RoomPhoto $photo): RedirectResponse
    {
        RoomPhoto::where('is_featured', true)->update(['is_featured' => false]);

        $photo->update(['is_featured' => true]);

        $room->update(['room_status_id' => 2]);

        return redirect()->back();
    }

    public function delete(Request $request, Room $room): RedirectResponse
    {
        if(!empty($request->checkedPhotos)){
            $photos = $room->photos()->whereIn('id', $request->checkedPhotos);
    
            $photo_paths = $photos->pluck('path')->toArray();
    
            Storage::disk('public')->delete($photo_paths);
    
            $photos->delete();
        }
        return redirect()->back();
    }
}
