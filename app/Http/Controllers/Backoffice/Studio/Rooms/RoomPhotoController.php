<?php

namespace App\Http\Controllers\Backoffice\Studio\Rooms;

use App\Http\Controllers\Controller;

use App\Models\Room\Room;
use App\Models\Room\RoomPhoto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class RoomPhotoController extends Controller
{
    public function edit(Room $room): Response
    {
        $photos = $room->photos;

        return Inertia::render('Backoffice/Studio/Rooms/Photos', compact('photos', 'room'));
    }

    public function update(Request $request, Room $room): RedirectResponse
    {
        $request->validate([
            'photos' => 'array',
            'photos.*.file' => 'image|max:3072',
        ]);

        $studio_id = $room->studio->id;

        if(!empty($request->photos)){
            foreach($request->photos as $key => $photo){
                if(!isset($photo['file'])){
                    $room->photos()->findOrFail($photo['id'])->update([
                        'sort_index' => $key +1,
                    ]);
                } else {
                    $path = 'studios/studio-' . $studio_id . '/rooms/' . $room->id;
                    $file_path = Storage::disk('public')->putFile($path, $photo['file']);

                    RoomPhoto::create([
                        'room_id' => $room->id,
                        'path' => $file_path,
                        'filename' => basename($file_path),
                        'sort_index' => $key +1,
                    ]);
                }
            }
        }

        return back()->with('success', 'Foto salvate');
    }

    public function destroy(Request $request, Room $room): RedirectResponse
    {
        $request->validate([
            'selected_photos' => ['required', 'array'],
            'selected_photos.*' => ['integer', 'exists:room_photos,id'],
        ]);

        if($request->selected_photos){
            foreach ($request->selected_photos as $photo_id) {
                $photo = $room->photos()->findOrFail($photo_id);
                Storage::disk('public')->delete($photo->path);
                $photo->delete();
            }
        }

        return back()->with('success', 'Foto eliminate');
    }
}
