<?php

namespace App\Http\Controllers\Backoffice\Studio;

use App\Http\Controllers\Controller;

use App\Models\Studio\Bundle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class BundlePhotoController extends Controller
{
    public function edit(Bundle $bundle): Response
    {
        $photo = $bundle->cover_path;

        return Inertia::render('Backoffice/Studio/Bundles/Photo', compact('photo', 'bundle'));
    }

    public function update(Request $request, Bundle $bundle): RedirectResponse
    {
        $request->validate([
            'photo' => 'image|max:2048',
        ]);

        $studio_id = $bundle->studio->id;

        if($bundle->cover_path){
            Storage::disk('public')->delete($bundle->cover_path);
        }

        $path = 'studios/studio-' . $studio_id . '/bundles/' . $bundle->id;
        $file_path = Storage::disk('public')->putFile($path, $request->photo);

        $bundle->update([
            'cover_path' => $file_path
        ]);

        return back()->with('success', 'Foto salvata');
    }

    public function destroy(Request $request, Bundle $bundle): RedirectResponse
    {
        if($bundle->cover_path){
            Storage::disk('public')->delete($bundle->cover_path);

            $bundle->update([
                'cover_path' => null
            ]);
        }

        return back()->with('success', 'Foto eliminata');
    }
}
