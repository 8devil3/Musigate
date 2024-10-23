<?php

namespace App\Http\Controllers\Backoffice\Studio;

use App\Http\Controllers\Controller;
use App\Models\Studio\StudioPhoto;
use App\Services\CheckStudioInfo;
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

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'photos' => 'array',
            'photos.*.file' => 'image|max:2048',
        ]);

        $studio = auth()->user()->studio;

        if(!empty($request->photos)){
            foreach($request->photos as $key => $photo){
                if(!isset($photo['file'])){
                    $studio->photos()->findOrFail($photo['id'])->update([
                        'sort_index' => $key +1,
                    ]);
                } else {
                    $path = 'studios/studio-' . $studio->id . '/photos';
                    $file_path = Storage::disk('public')->putFile($path, $photo['file']);

                    StudioPhoto::create([
                        'studio_id' => $studio->id,
                        'path' => $file_path,
                        'filename' => basename($file_path),
                        'sort_index' => $key +1,
                    ]);
                }
            }
        }

        CheckStudioInfo::update_studio($studio);

        return back()->with('success', 'Foto salvate');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'selected_photos' => ['required', 'array'],
            'selected_photos.*' => ['integer', 'exists:studio_photos,id'],
        ]);
        
        if($request->selected_photos){
            $studio = auth()->user()->studio;

            foreach ($request->selected_photos as $photo_id) {
                $photo = $studio->photos()->findOrFail($photo_id);
                Storage::disk('public')->delete($photo->path);
                $photo->delete();
            }

            CheckStudioInfo::update_studio($studio);
        }

        return back()->with('success', 'Foto eliminate');
    }
}
