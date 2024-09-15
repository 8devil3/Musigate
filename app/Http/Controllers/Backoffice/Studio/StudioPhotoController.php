<?php

namespace App\Http\Controllers\Backoffice\Studio;

use App\Http\Controllers\Controller;
use App\Http\Requests\PhotoRequest;
use App\Models\Studio\StudioPhoto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class StudioPhotoController extends Controller
{
    public function edit(): Response
    {
        $photos = auth()->user()->studio->photos;

        return Inertia::render('Backoffice/Studio/Photos', compact('photos'));
    }

    public function update(PhotoRequest $request): RedirectResponse
    {
        $request->validated();

        $user = auth()->user();

        foreach($request->photos as $photo){
            $path = Storage::disk('public')->putFile('users/user-' . $user->id . '/studio/photos', $photo);

            StudioPhoto::create([
                'studio_id' => $user->studio->id,
                'path' => $path,
            ]);
        }

        return redirect()->back();
    }

    public function featured(StudioPhoto $photo): RedirectResponse
    {
        StudioPhoto::where('is_featured', true)->update(['is_featured' => false]);

        $photo->update(['is_featured' => true]);

        return redirect()->back();
    }

    public function delete(Request $request): RedirectResponse
    {
        if(!empty($request->checkedPhotos)){
            $photos = StudioPhoto::whereIn('id', $request->checkedPhotos);
    
            $photo_paths = $photos->pluck('path')->toArray();
    
            Storage::disk('public')->delete($photo_paths);
    
            $photos->delete();
        }

        return redirect()->back();
    }
}
